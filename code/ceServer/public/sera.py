# -*- coding: utf-8 -*-
"""
Created on Tue Nov 28 22:42:57 2017

@author: 坤
"""
import os 
import numpy as np
import pandas as pa
import math as Math
import sys
import pymysql
import dateutil




'''
过滤大震附近小震
参数 地址目录 时间 以及半径
'''
def findEqM(df,minz,maxz):
    find=df[df['magnitude']>=int(minz)]
    return find[find['magnitude']<int(maxz)]

def getRadian(degree):
     return degree * Math.pi / 180.0



def distance(lat1,lon1,lat2,lon2):
     EARTH_RADIUS = 6378.137
     radLat1 = getRadian(lat1)  
     radLat2 = getRadian(lat2)  
     a = radLat1 - radLat2#// 两点纬度差  
     b = getRadian(lon1) - getRadian(lon2)#// 两点的经度差  
     a=np.array(a)
     b=np.array(b)
     s = 2 * np.arcsin(np.sqrt(np.power(np.sin(a / 2), 2) + np.cos(radLat1)* np.cos(radLat2) * np.power(np.sin(b / 2), 2)));  
     s = s * EARTH_RADIUS
     return s ; 


def flter(data,Bdata,time,radii):
    try:
        MinTime=data.eqtime-dateutil.relativedelta.relativedelta(days=time)
        MaxTime=data.eqtime#+dateutil.relativedelta.relativedelta(days=time)   
        BdataCopy=Bdata.copy()     
        BdataCopy=BdataCopy[(BdataCopy.eqtime>=MinTime)&(BdataCopy.eqtime<=MaxTime)]
    
        BdataCopy.depth=0
        BdataCopy.depth=distance(data.latitude,data.longitude,BdataCopy.loc[:,'latitude'],BdataCopy.loc[:,'longitude'])   
        BdataCopy=BdataCopy[BdataCopy.depth<=radii]

        Bdata.drop(BdataCopy.index.tolist(),axis=0,inplace=True)
    except BaseException:
        BdataCopy=pa.DataFrame()
    return BdataCopy,Bdata 

dz=pa.Series([sys.argv[1],float(sys.argv[2]),float(sys.argv[3]),0,0],index = ["eqtime","longitude","latitude","magitude","depth"]) 
dz.eqtime=pa.to_datetime(dz.eqtime)
MinTime=dz.eqtime-dateutil.relativedelta.relativedelta(days=365)
MaxTime=dz.eqtime#+dateutil.relativedelta.relativedelta(days=time) 
conn = pymysql.connect(host='172.17.130.212', user='root', password='gis502', database='cloud')
df = pa.read_sql("select * from base where eqtime>='"+str(MinTime)+"' and eqtime<'"+str(MaxTime)+"';", con=conn)
df['eqtime']=pa.to_datetime(df['eqtime'])
df.drop(['eqnum','type','state'],axis=1,inplace=True)
eq,eq2=flter(dz,df,365,100)
df = pa.read_sql("select * from eqrate where omen<='"+str(eq.shape[0])+"';", con=conn)
#print(dz)
print(df.iloc[-1:,3].values[0])
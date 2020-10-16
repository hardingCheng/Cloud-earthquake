<template>
<div>
  <div class="header">
      <ul>
          <li class="logo">
            <img src="../assets/logo.png" alt="">
          </li>
          <router-link to="/"><li class="bli">首页
          
          </li></router-link>
          <router-link to="/charts">
          <li class="bli">图表分析
          </li></router-link>
          
          <li @click="analysis= true" class="bli">地震趋势分析</li>
          <li @click="aftershock = true" class="bli">余震预测</li>
      </ul>
    </div>
    <div class="demo-input-size">
      <el-dialog title="地震趋势分析" :visible.sync="analysis" width="40%" style="margin-top:65px">
        <p style="text-align:center">
          <label for="日期">日期</label>
          <input type="date"  v-model="analysisdata.data" >
        </p>
        <p style="text-align:center">
        <label for="经度">经度</label>
        <input type="Number" v-model="analysisdata.lon">
        </p>
        <p style="text-align:center">
        <label for="纬度">纬度</label>
        <input type="Number" v-model="analysisdata.lat">
        </p>
        <span slot="footer" class="dialog-footer">
          <el-button @click="analysis = false">取 消</el-button>
          <el-button type="primary" @click="getAnalysis">确 定</el-button>
       </span>
      </el-dialog>
    </div>
    <div>
      <el-dialog title="余震预测分析" :visible.sync="aftershock" width="40%" style="margin-top:49px" >
        <p style="text-align:center">
          <label for="起震时间">起震时间</label>
          <input v-model="aftershockdata.starttime" type="date">
        </p>
        <p style="text-align:center">
        <label for="预测时间">预测时间</label>
        <input v-model="aftershockdata.endtime"  type="date">
        </p>
        <p style="text-align:center">
        <label for="发震震级">发震震级</label>
        <input v-model="aftershockdata.startmag"  type="Number" min="1" max="8">
        </p>
        </p>
        <p style="text-align:center">
        <label for="预测震级">预测震级</label>
        <input v-model="aftershockdata.endmag"  type="Number" min="1" max="8">
        </p>
        <span slot="footer" class="dialog-footer">
          <el-button @click="aftershock = false">取 消</el-button>
          <el-button type="primary" @click="getAftershock">确 定</el-button>
       </span>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import Vue from "vue";
import Element from "element-ui";
Vue.use(Element, { size: "small" });

const axios = require("axios");
export default {
  data() {
    return {
      analysis: false,
      aftershock:false,
      analysisdata: {
        data: "2017-12-31",
        lat: 30.45,
        lon: 102.51
      },
      aftershockdata:{
        starttime:  "2017-01-01",
        endtime:"2017-12-31",
        startmag:5,
        endmag:6.5
      }
    };
  },
  watch:{
    analysisdata:{
      handler(val){
        if(val.lat > 180 || val.lat<-180){
          val.lat = 30.45
          this.$message({
            message: '请输入正确的经纬度',
            type: 'warning'
          });
        }
        if(val.lon > 180 || val.lon<-180){
          val.lon = 30.45
          this.$message({
            message: '请输入正确的经纬度',
            type: 'warning'
          });
        }
      },
      deep: true
    },
    aftershockdata:{
      handler(val,oldVal){
        if(val.startmag < 0 || val.startmag > 8){
          val.startmag = 5
          this.$message({
            message: '请输入正确的震级',
            type: 'warning'
          });
        }
        if(val.endmag < 0 || val.endmag > 8){
          val.endmag = 6.5
          this.$message({
            message: '请输入正确的震级',
            type: 'warning'
          });
        }
        const newdate = new Date();
        const olddata = new Date(val.endtime);
        if(olddata < newdate){
          val.endtime = "2017-12-31"
          this.$message({
            message: '预测时间需大于系统时间',
            type: 'warning'
          });
        }
      },
      deep:true
    }
  },
  methods: {
    getAnalysis() {
      this.$emit('changeLoading');
      this.analysis = false;
      const analysisRusults =
        "http://172.17.130.212:8082/ceServer/public/eqrart/"+this.analysisdata.data+"/"+this.analysisdata.lat+"/"+this.analysisdata.lon;
      axios
        .get(analysisRusults)
        .then(
          function(response) {
            this.$emit('changeLoading');
            if (response.data.reason === "Success") {
              this.$alert(this.analysisdata.data+"当天在经度："+this.analysisdata.lon+"纬度："+this.analysisdata.lat+"发生地震的概率为："+response.data.result, '算法分析预测结果', {
                confirmButtonText: '确定'
              })
            } else {
              throw new Error("数据请求失败");
            }
          }.bind(this)
        )
        .catch(function(error) {
          console.log(error);
        });
        
    },
    getAftershock(){
      var systemDate=new Date();
     var systemYear = systemDate.getFullYear();  
      var systemMonth = systemDate.getMonth()+1;  
      var systemDay = systemDate.getDate();  
      var system= systemYear+'-'+systemMonth+'-'+(systemDay<10?"0":"")+systemDay;
      this.$emit('changeLoading');
      this.aftershock = false;
       const aftershockRusults = "http://172.17.130.212:8082/ceServer/public/earthquakePrediction/"+this.aftershockdata.starttime+
       "/"+this.aftershockdata.startmag+"/"+this.aftershockdata.endmag+"/"+this.daysBetween(this.aftershockdata.starttime,this.aftershockdata.endtime);
      axios
        .get(aftershockRusults)
        .then(
          function(response) {
            this.$emit('changeLoading');
            this.loading = false;
            if (response.data.reason === "Success") {
              if (response.data.result === 0) {
                  this.$message({
                    message: '近几月地震数据量较少，请您选择三个月前数据。',
                    type: 'warning'
                  });
              }
              else{
                  this.$alert('未来'+this.daysBetween(this.aftershockdata.endtime,system)+'天内发生'+this.aftershockdata.endmag+'级地震的概率是'+response.data.result, '算法分析预测结果', {
                confirmButtonText: '确定'
              })
              }
              
            } else {
              throw new Error("数据请求失败");
            }
          }.bind(this)
        )
        .catch(function(error) {
          console.log(error);
        });
        
    },
    daysBetween:function (DateOne,DateTwo)  
    {  
      var OneMonth = DateOne.substring(5,DateOne.lastIndexOf ('-'));  
      var OneDay = DateOne.substring(DateOne.length,DateOne.lastIndexOf ('-')+1);  
      var OneYear = DateOne.substring(0,DateOne.indexOf ('-'));  
      var TwoMonth = DateTwo.substring(5,DateTwo.lastIndexOf ('-'));  
      var TwoDay = DateTwo.substring(DateTwo.length,DateTwo.lastIndexOf ('-')+1);  
      var TwoYear = DateTwo.substring(0,DateTwo.indexOf ('-'));  
      var cha=((Date.parse(OneMonth+'/'+OneDay+'/'+OneYear)- Date.parse(TwoMonth+'/'+TwoDay+'/'+TwoYear))/86400000);   
      return Math.abs(cha);  
    } 
  }
};
</script>

<style>
.header {
  height: 6%;
  background-color: #293c55;
}
ul {
  margin: 0;
  padding: 0;
  height: 60px;
}
.logo {
  float: left;
  margin-right: 10px;
}
.bli {
  cursor: pointer;
  list-style: none;
  float: left;
  height: 60px;
  line-height: 60px;
  width: 110px;
  text-align: center;
  color: #8994a2;
}
.bli:hover {
  background-color: #0e141e;
  color: #fff;
  border-left: 2px solid red;
}
a {
  text-decoration: none;
  color: #8994a2;
}
a:hover {
  color: #fff;
}
.logo img {
  height: 60px;
}
element.style {
    width: 42%;
    margin-top: 27vh;
    height: 44%;
}

p{
  font-size: 20px;
}
.el-dialog__body {
    padding: 0;
    color: #5a5e66;
    line-height: 43px;
    font-size: 17px;

}
.el-button--small, .el-button--small.is-round {
    padding: 10px 21px 14px 27px;
}
.el-dialog__footer {
    padding: 29px 45px 15px;
    text-align: right;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.el-dialog__title {
    line-height: 47px;
    font-size: 22px;
    color: #407596;
}
input {
    position: relative;
    float: right;
    right: 24%;
    width: 27.5%;
    height: 20%;
    bottom: -12px;
}
label {
    margin-right: 77px;
    position: relative;
}
</style>
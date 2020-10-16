<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;


Route::get('/',function(){  
    return 'cloudearthquake系统API';  
});
Route::get('baseData', 'index/Basechannel/getBaseData');
Route::get('getEarthquake/:begintime/:endtime', 'index/Basechannel/getEarthquake');
Route::get('getFeltData/:begintime/:endtime', 'index/Basechannel/getFeltData');
Route::get('getLevelData', 'index/Basechannel/getLevelData');
Route::get('dataView','index/Basechannel/getDataView');
Route::get('earthquakePrediction/:realTime/:starMagnitude/:predictMagnitude/:predictTime', 'index/Basechannel/earthquakePrediction');
Route::get('getEarthquakeNumber','index/Basechannel/getEarthquakeNumber');
Route::get('groupByMg','index/Basechannel/groupByMg');
Route::get('eqrart/:time/:lon/:lat','index/Basechannel/eqrart');
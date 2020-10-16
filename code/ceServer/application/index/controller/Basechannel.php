<?php
namespace app\index\controller;
use think\controller\Rest;
use think\Request;
use think\Db;
/**
* 
*/
class Basechannel extends Rest
{
	// public function getBaseData() {
	// 	$map = array(
	// 		'magnitude'	=>	['>', 5]
	// 	);
	// 	$result = Db::table('base')->where($map)->select();
	// 	$res = new Dataencapsulation($result);
	// 	$data = $res->packdata();
	// 	return $data;
	// }
	public function getBaseData() {
		$map = array(
			'magnitude'	=>	['>', 5]	
		);
		$result = Db::table('base')->where($map)->select();
		$res = new Dataencapsulation();
		$data = $res->packdata($result,'200','Success');
		return $data;
	}
	public function eqrart($time,$lon,$lat){
        $aa=exec("python ..\public\sera.py {$time} {$lon} {$lat}",$out,$res);
        $res=($res==0)?'Success':'Error';
        $arr=array(
        	'reason'=>$res,
        	'result'=>$out[0],
        	'status'=>200
        );
        $arr= json_encode($arr);
        echo $arr;
    }
	
	public function getEarthquake($begintime,$endtime)
	{
		$map[0] = array(
		'magnitude'	=>	['between', '3,3.9'],
		'eqtime'=> ['between',[$begintime,$endtime]]	
		);
		$map[1] = array(
		'magnitude'	=>	['between', '4,4.9'],
		'eqtime'=> ['between',[$begintime,$endtime]]	
		);
		$map[2] = array(
		'magnitude'	=>	['between', '5,5.9'],
		'eqtime'=> ['between',[$begintime,$endtime]]	
		);
		$map[3] = array(
		'magnitude'=>	['between', '6,6.9'],
		'eqtime'=> ['between',[$begintime,$endtime]]	
		);
		$map[4] = array(
		'magnitude'	=>	['between', '7,7.9'],
		'eqtime'=> ['between',[$begintime,$endtime]]	
		);
		$map[5] = array(
		'magnitude'	=>	['between', '8,8.9'],
		'eqtime'=> ['between',[$begintime,$endtime]]	
		);
		$result=array();
		for($i=0;$i<6;$i++)
		{
			$result[$i] = Db::table('base')->where($map[$i])->count('magnitude');
		}
		$res = new Dataencapsulation();
		$data = $res->packdata($result,'200','Success');
		return $data;
	}
	public function getFeltData($begintime,$endtime) {
		$map = array(
		'eqtime'=> ['between',[$begintime,$endtime]]	
		);
		$result=array();
		$result[0] = Db::table('basei')->where($map)->count('type');
		$result[1] = Db::table('baseii')->where($map)->count('type');
		$result[2] = Db::table('baseiii')->where($map)->count('type');
		$result[3] = Db::table('baseiv')->where($map)->count('type');
		$result[4] = Db::table('basev')->where($map)->count('type');
		$res = new Dataencapsulation();
		$data = $res->packdata($result,'200','Success');
		return $data;
	}
	public function groupByMg()
	{
		$result= Db::table('base1117')->select();
		$datas = array();
		for($i = 0;$i < 15; $i ++){
			$datas[$i] = array();
		}
		for($j = 0;$j <15; $j ++)
		{
			
			array_push($datas[$j],$result[$j]['mag1']);
			array_push($datas[$j],$result[$j]['mag2']);
			array_push($datas[$j],$result[$j]['mag3']);
			array_push($datas[$j],$result[$j]['mag4']);
			array_push($datas[$j],$result[$j]['mag5']);
			array_push($datas[$j],$result[$j]['mag6']);
			array_push($datas[$j],$result[$j]['mag7']);
			array_push($datas[$j],$result[$j]['mag8']);
		}
		$res = new Dataencapsulation();
		$data = $res->packdata($datas,'200','Success');
		return $data;
	}
	public function getEarthquakeNumber()
	{
		$result= Db::table('base1117')->select();
		$title = array();
		$datas = array();
		for($i = 0;$i < sizeof($result); $i ++){
			array_push($title,$result[$i]['eqtime']);
		}
		for($i = 0;$i < 8; $i ++){
			$datas[$i] = array();
		}
		for($j = 0;$j <15; $j ++)
		{
			
			array_push($datas[0],$result[$j]['mag1']);
			array_push($datas[1],$result[$j]['mag2']);
			array_push($datas[2],$result[$j]['mag3']);
			array_push($datas[3],$result[$j]['mag4']);
			array_push($datas[4],$result[$j]['mag5']);
			array_push($datas[5],$result[$j]['mag6']);
			array_push($datas[6],$result[$j]['mag7']);
			array_push($datas[7],$result[$j]['mag8']);
		}
		$info['title'] = $title;
		$info['data'] = $datas;
		$res = new Dataencapsulation();
		$data = $res->packdata($info,'200','Success');
		return $data;
	}
	public function getLevelData()
	{
			$result= Db::table('base1117')->select();
		$title = array();
		$datas = array();
		for($i = 0;$i < sizeof($result); $i ++){
			$datas[$i] = array();
			array_push($title,$result[$i]['eqtime']);
			array_push($datas[$i],$result[$i]['mag1']);
			array_push($datas[$i],$result[$i]['mag2']);
			array_push($datas[$i],$result[$i]['mag3']);
			array_push($datas[$i],$result[$i]['mag4']);
			array_push($datas[$i],$result[$i]['mag5']);
			array_push($datas[$i],$result[$i]['mag6']);
			array_push($datas[$i],$result[$i]['mag7']);
			array_push($datas[$i],$result[$i]['mag8']);
		}
		$info['title'] = $title;
		$info['data'] = $datas;
		$res = new Dataencapsulation();
		$data = $res->packdata($info,'200','Success');
		return $data;
	}
	public function getDataView(){
		for($i = 2003;$i<=2017;$i++){
			$date[$i-2003] = array(
				'eqtime' => ['between time',$i.'-01-01,'.$i.'-12-31'],
				'magnitude'	=>	['>', 3]
			);
		}
		$midinfo = array();
		for ($i = 0; $i < 15; $i ++){
			$info = Db::table('base')->where($date[$i])->select();
			for($j = 0;$j < sizeof($info); $j ++){			
				array_push($midinfo,[(double)$info[$j]['latitude'],(double)$info[$j]['longitude'],(double)$info[$j]['magnitude']]);
			}
			$result[$i] = $midinfo;
			$midinfo = array();
		}
		$res = new Dataencapsulation();
		$data = $res->packdata($result,200,"Success");
		return $data;
	}
	public function earthquakePrediction($realTime,$starMagnitude,$predictMagnitude,$predictTime) {
		// 转换为double型
		$t = floatval($predictTime);
		// 起算震级
		$mg0 = floatval($starMagnitude);
		// 预测震级
		$mg1 = floatval($predictMagnitude);
		// 格式化日期
		$realTime = date_create($realTime);
		$endTime = date_format($realTime,"Y-m-d");
		// 求前三个月
		$starTime = date("Y-m-d",strtotime("$endTime -3 month"));
		// 计算相差天数
		$day=floor((strtotime($endTime)-strtotime($starTime))/86400);
		// 查库
		$time = array(
			'eqtime' => ['between',[$starTime,$endTime]]
		);
		$eqResult = Db::table('base')->where($time)->select();
		// 分析
		if(count($eqResult) > 2) {
			// getP01
			$N1 = 0;
			$N10 = 1;
			$n = 0;
			$N1 = floatval($N1);
			$N10 = floatval($N10);
			for($i = 0;$i < count($eqResult);$i++) {
				$mg = $eqResult[$i]['magnitude'];
				if($mg > $mg0) {
					$n++;
					if($mg >= $mg1) {
						$N1++;
					}
					else {
						$N10++;
					}
				}
			}
			// if($N10 > 0) {
				$p01 = floatval($N1/$N10);
			// }
			// else {
			// 	$p01 = 0;
			// }
			// getQ0
			$q0 = floatval($n/$day);
			$pr = 1.0-exp(-$q0 * $p01 * $t);
			$probability = number_format($pr, 2, '.', '');
			$result = $probability."%";
		}
		else {
			$result = 0;
		}
		$res = new Dataencapsulation();
		$data = $res->packdata($result,200,"Success");
		return $data;
	}
}
?>
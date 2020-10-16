<?php
namespace app\index\controller;
use think\Controller;


/**
* 
*/
class Dataencapsulation extends Controller
{
	// private $DATA;
	// private $STATUS;
	// private $REASON;

	// public function __construct($_data, $STATUS = '0', $REASON = 'Success')
    // {
    //     $this->DATA = $_data;
    //     $this->STATUS = $STATUS;
    //     $this->REASON = $REASON;
    // }

    // public function packdata() {
    // 	$data = array(
    // 		'status'	=>	$this->STATUS,
    // 		'reason'	=>	$this->REASON,
    // 		'result'	=>	$this->DATA
    // 	);
    // 	return json_encode($data);
	// }
	private $DATA;
	private $STATUS;
	private $REASON;

	public function __construct()
	//构造方法是类中的一个特殊方法。当使用 new 操作符创建一个类的实例时，构造方法将会自动调用，其名称必须是 __construct() 。
    {

	}
	
    public function packdata($_data,$status,$reason) {
		$data1 = array(
			'status'=>$status,
    		'reason'=>$reason,
			'result'=>$_data
		);
    	return json_encode($data1);
	}
	public function packdata1($_data,$status,$reason) {
		$data1 = array(
			'status'=>$status,
    		'reason'=>$reason,
			'result'=> array(
				$_data
			)
		);
    	return json_encode($data1);
	}
}
?>
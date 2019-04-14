<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 14:10:48
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-04-14 12:24:12
 */
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class Service extends \app\Home\controller\Base
{
	protected $schoolCode = 12714;
	protected $domain_path = 'http://127.0.0.1/composerProject/logistics-management/uploads/';
    public function doRepair(){
    	$data =  Request::post(); 
    	$username = Request::post('user_name'); 
     	$validate = new \app\home\validate\Service();
     	//查询管理员
     	$map['status'] = 1;
     	$admin=Db::name('admin')->where($map)->where('area',$data['area_'])->orderRand()->find();  //随机查询符合区域的管理员
		$setProcess = setProcess($username,1,$data['area'],$admin['name'],'','');
		$data['date'] = time();
    	$data['repair_num'] = setRepair_num('Service');
    	$data['repair_id'] = create_guid();
    	$data['area'] = $data['area'].$data['area_detail'];
    	$data['admin_id'] =$admin['admin_id'];
		//把订单id加进去
		$setProcess['service_id'] = $data['repair_id'];
		// var_dump($setProcess);
		// exit();
        if(!$validate->check($data)){
            $error_msg = $validate->getError();
            return returnData('error',-1,$error_msg);
        }
        try{
        	// $service = new Service;
		    $rst = Db::name('service')->strict(false)->data($data,true)->insert();   //allow过滤user_name
			$process = Db::name('process')->data($setProcess,true)->insert();
		}catch(\Exception $e){
		    return returnData('error',-1,$e->getMessage());
		}
		return returnData('',1,'提交成功');
    }

	public function getRepairList(){
		$data=Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
		try {
			// $process = implode(",", $data['process']);
			$map['user_id']  = array('in',$data['user_id']);
			$map['process'] = $data['process'];
 			$data = Db::name('service')->where($map)->order('date','desc')->failException(false)->select();  //为空不报错
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData($data,1,'处理成功');
	}
	
	public function getRepairDetails(){
		$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
		try {
			$map['repair_id'] = $data['repair_id'];
			$map2['service_id'] = $data['repair_id'];
			$data = Db::name('service')->where($map)->failException(false)->find();
			$data['processInfo'] = Db::name('process')->where($map2)->order('id','desc')->failException(false)->select();
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData($data,1,'处理成功');
	}


	// 派工操作
	public function dispatchedWorker(){
		$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
		$validate = new \app\home\validate\Process();
		if(!$validate->check($data)){
            $error_msg = $validate->getError();
            return returnData('error',-1,$error_msg);
        }

		try {
			// $worker_id = $data['worker_id'];
			$worker_name = $data['worker_name'];
			$admin_name = $data['admin_name'];
			// $admin_id = $data['admin_id'];
			$service_id = $data['service_id'];
			// 更新service数据
			// $servieUpdate['admin_id'] = $data['admin_id'];
			$servieUpdate['worker_id'] = $data['worker_id'];
			$servieUpdate['process'] = 3;
			$map['repair_id'] = $data['service_id'];
			$step1 = setProcess('',2,'',$admin_name,'','');
			$step1['service_id'] = $data['service_id'];
			$step2 = setProcess('',3,'',$admin_name,$worker_name,'');
			$step2['service_id'] = $data['service_id'];
			try {
				$process1 = Db::name('process')->data($step1,true)->insert();  //审批进度
			} catch (Exception $e) {
				return returnData('error',-1,$e->getMessage());
			}
			try {
				$process2 = Db::name('process')->data($step2,true)->insert();  //派工进度
			} catch (Exception $e) {
				return returnData('error',-1,$e->getMessage());
			}
				$rst = Db::name('service')-> where($map) -> setField($servieUpdate);  //更新service进度
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData('',1,'处理成功');
	}

	//完成维修
	public function finishWork($service_id){
		$data = Request::post();
		$map['repair_id'] = $data['service_id'];
		$step3 = setProcess('',5,'','',$data['worker_name'],'');
		$step3['service_id'] = $data['service_id'];
		$serviceInfo = Db::name('service')->where($map)->field('user_id,user_name,worker_id')->find();
		$message = setMessage($serviceInfo['worker_id'],$data['worker_name'],$serviceInfo['user_id'],$serviceInfo['user_name']);
		$servieUpdate['process'] = 5;  //进度
		try {
			$process3 = Db::name('process')->data($step3,true)->insert();  //审批进度
			$sendMessage = Db::name('message')->data($message,true)->insert(); //发送消息
			$rst = Db::name('service')-> where($map) -> setField($servieUpdate);  //更新service进度
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData('',1,'处理成功');
	}
	// 评价操作
	public function doRater(){
		$data = Request::post();
		$map['repair_id'] = $data['service_id'];
		$servieUpdate['admin_rater'] = $data['admin_rater'];
		$servieUpdate['worker_rater'] = $data['worker_rater'];
		try {
			$rst = Db::name('service')-> where($map) -> setField($servieUpdate);  
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData('',1,'处理成功');
	}

}

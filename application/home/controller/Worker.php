<?php
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class Worker
{
	public function getWorkerList(){
		$data = Request::post();
		try {
			$type = '%'.$data['type'].'%';
			$area = '%'.$data['area'].'%';
			$map['status'] = ['in',1,0];
			$workerList =  Db::name('worker')->where($map)->where('area','like',$area)->where('type','like',$type)->failException(false)->select();
			return returnData($workerList,1,'处理成功');
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
	}

	public function doAddWorker(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $validate = new \app\home\validate\Worker();
  	    if(!$validate->check($data)){
            $error_msg = $validate->getError();
            return returnData('error',-1,$error_msg);
        }
        try{
        	$data['worker_id'] = create_guid();
        	$data['password'] = MD5($data['tel']);
        	// $service = new Service;
		    $rst = Db::name('worker')->strict(false)->data($data,true)->insert();   //allow过滤user_name
		}catch(\Exception $e){
		    return returnData('error',-1,$e->getMessage());
		}
		return returnData('',1,'提交成功');
	}


	public function getWorkerInfo(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $map['id'] = $data['id'];
  	    try {
			$adminInfo =  Db::name('worker')->where($map)->failException(false)->find();
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData($adminInfo,1,'处理成功');
	}

	public function changeWorkerStatus(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $map['id'] = $data['id'];
  	    try {
  	    	$rst = Db::name('worker')->where($map)->setField($data);
  	    } catch (Exception $e) {
  	    	return returnData('error',-1,$e->getMessage());
  	    }
  	    return returnData('',1,'处理成功');
	}

	//超级管理员更改维修工信息
    public function updateOneWorkerInfo(){
    	$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
	    }
	    $newData['type'] = $data['type'];
	    $newData['area'] = $data['area'];
	    $map['id'] = $data['id'];
	    try {
	    	$rst = Db::name('worker')-> where($map) -> setField($data);
	    } catch (Exception $e) {
	    	return returnData('error',-1,$e->getMessage());
	    }
	    return returnData('',1,'处理成功');
    }

    
}
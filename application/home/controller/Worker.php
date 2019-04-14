<?php
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class Worker extends \app\Home\controller\Base
{
	public function doLogin(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $map['tel'] = $data['tel'];
  	    $isWorker = Db::name('worker')->where($map)->select();
  	    if(!$isWorker){
  	    	return returnData('error',-1,'维修工不存在');
  	    }else{
  	    	$workerInfo = Db::name('worker')->where($map)->failException(false)->find();
       		if(MD5($data['password'])!=$workerInfo['password']){
       			return returnData('error',-1,'密码错误');
       		}else{
       			if($workerInfo['status']!=1){
       				return returnData('error',-1,'您已被删除或禁用，请联系管理员');
       			}else{
       				return returnData($workerInfo,1,'处理成功');
       			}
       		}
  	    }
	}

	public function updateInfo(){
		$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
	    }
        $validate = new \app\home\validate\Worker();
        if(!$validate->check($data)){
            $error_msg = $validate->getError();
            return returnData('error',-1,$error_msg);
        }
    	$map['id'] = $data['id'];
    	if(strlen($data['name'])>18){
    		return returnData('error',-1,'姓名不能超过6个字符');
    		return false;
    	}
    	try {
    		$rst = Db::name('worker')-> where($map) -> setField($data);
    	} catch (Exception $e) {
    		return returnData('error',-1,$e->getMessage());
    	}
    	return returnData('',1,'处理成功');
    }
    public function updatePassword(){
    	$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
	    }
	    $password=$data['password'];
	    if($password!=''){
	    	if(strlen($password)>16){
	    		return returnData('error',-1,'密码长度超过16字符');
	    	}
	    }
	    $map['id'] = $data['id'];
	    try {
	    	$data['password'] = md5($password);
	    	$rst = Db::name('worker')-> where($map) -> setField($data);
	    } catch (Exception $e) {
	    	return returnData('error',-1,$e->getMessage());
	    }
	    return returnData('',1,'处理成功');
    }

	public function getWorkerList(){
		$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
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
  	    $map['worker_id'] = $data['worker_id'];
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
  	    $map['worker_id'] = $data['worker_id'];
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
	    $map['worker_id'] = $data['worker_id'];
	    try {
	    	$rst = Db::name('worker')-> where($map) -> setField($data);
	    } catch (Exception $e) {
	    	return returnData('error',-1,$e->getMessage());
	    }
	    return returnData('',1,'处理成功');
    }

    
}
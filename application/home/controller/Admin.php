<?php
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class Admin
{
	public function doLogin(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $map['tel'] = $data['tel'];
  	    $isAdmin = Db::name('admin')->where($map)->select();
  	    if(!$isAdmin){
  	    	return returnData('error',-1,'管理员不存在');
  	    }else{
  	    	$adminInfo = Db::name('admin')->where($map)->failException(false)->find();
       		if(MD5($data['password'])!=$adminInfo['password']){
       			return returnData('error',-1,'密码错误');
       		}else{
       			return returnData($adminInfo,1,'处理成功');
       		}
  	    }
	}


	public function getAdminList(){
		$data = Request::post();
		try {
			$map['status'] = ['in',1,0];
			$adminList =  Db::name('admin')->where($map)->failException(false)->select();
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData($adminList,1,'处理成功');

	}

	public function getAdminInfo(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $map['id'] = $data['id'];
  	    try {
			$adminInfo =  Db::name('admin')->where($map)->failException(false)->find();
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData($adminInfo,1,'处理成功');
	}

	public function changeAdminStatus(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $map['id'] = $data['id'];
  	    try {
  	    	$rst = Db::name('admin')->where($map)->setField($data);
  	    } catch (Exception $e) {
  	    	return returnData('error',-1,$e->getMessage());
  	    }
  	    return returnData('',1,'处理成功');
	}

	public function doAddAdmin(){
		$data = Request::post();
		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	    }
  	    $validate = new \app\home\validate\Admin();
  	    if(!$validate->check($data)){
            $error_msg = $validate->getError();
            return returnData('error',-1,$error_msg);
        }
        try{
        	$data['admin_id'] = create_guid();
        	$data['password'] = MD5($data['tel']);
        	// $service = new Service;
		    $rst = Db::name('admin')->strict(false)->data($data,true)->insert();   //allow过滤user_name
		}catch(\Exception $e){
		    return returnData('error',-1,$e->getMessage());
		}
		return returnData('',1,'提交成功');
	}

	public function updateInfo(){
		$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
	    }
        $validate = new \app\home\validate\Admin();
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
    		$rst = Db::name('admin')-> where($map) -> setField($data);
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
	    	$rst = Db::name('admin')-> where($map) -> setField($data);
	    } catch (Exception $e) {
	    	return returnData('error',-1,$e->getMessage());
	    }
	    return returnData('',1,'处理成功');
    }

    //超级管理员更改普通管理员信息
    public function updateOneAdminInfo(){
    	$data = Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
	    }
	    $newData['level'] = $data['level'];
	    $newData['area'] = $data['area'];
	    $map['id'] = $data['id'];
	    try {
	    	$rst = Db::name('admin')-> where($map) -> setField($data);
	    } catch (Exception $e) {
	    	return returnData('error',-1,$e->getMessage());
	    }
	    return returnData('',1,'处理成功');
    }

    
}
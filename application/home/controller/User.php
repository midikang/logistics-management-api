<?php
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class User extends \app\Home\controller\Base
{
    public function doLogin(){
       $data=Request::post();
       if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	   }
       $validate = new \app\home\validate\User();
       $map['student_id'] = $data['student_id'];
       $hasReg = Db::name('student')->where($map)->find();
       //用户是否存在
       if(!$hasReg){
       		if(!$validate->check($data)){
	            $error_msg = $validate->getError();
	            return returnData('error',-1,$error_msg);
	        }
	        try{
	        	$data['student_pwd'] = MD5($data['student_pwd']);
	        	$data['student_name'] = '三好学生';
			      $rst = Db::name('student')->strict(false)->data($data,true)->insert();  
			}catch(\Exception $e){
			    return returnData('error',-1,$e->getMessage());
			}
			return returnData($data,1,'处理成功');
       }else{
       		$userInfo = Db::name('student')->where($map)->failException(false)->find();
       		if(MD5($data['student_pwd'])!=$userInfo['student_pwd']){
       			return returnData('error',-1,'密码错误');
       		}
       		return returnData($userInfo,1,'处理成功');
       }
    }

    public function updateInfo(){
  		$data = Request::post();
  		if(count($data)==0){
  			return returnData('error',-1,'[参数错误]');
  	  }
      if(strlen($data['tel'])!=11){
        return returnData('error',-1,'手机号不合法');
        return false;
      }
    	$map['student_id'] = $data['student_id'];
    	if(strlen($data['student_name'])>18){
    		return returnData('error',-1,'姓名不能超过6个字符');
    		return false;
    	}
    	try {
    		$rst = Db::name('student')-> where($map) -> setField($data);
    	} catch (Exception $e) {
    		return returnData('error',-1,$e->getMessage());
    	}
    	return returnData('',1,'处理成功');
    }

    public function getUserArea(){
      $data = Request::post();
      if(count($data)==0){
        return returnData('error',-1,'[参数错误]');
      }
      $map['student_id'] = $data['student_id'];
      try {
        $areaData = Db::name('userarea')->where($map)->find();
      } catch (Exception $e) {
        return returnData('error',-1,$e->getMessage());
      }
      return returnData($areaData,1,'处理成功');
    }

    public function addArea(){
      $data = Request::post();
      if(count($data)==0){
        return returnData('error',-1,'[参数错误]');
      }
      try {
        $rst = Db::name('userarea')->strict(false)->data($data,true)->insert();  
      } catch (Exception $e) {
        return returnData('error',-1,$e->getMessage());
      }
      return returnData($rst,1,'处理成功');
    }
}

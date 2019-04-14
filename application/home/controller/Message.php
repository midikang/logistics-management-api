<?php

/**
 * @Author: shiwan
 * @Date:   2019-04-14 12:59:44
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-04-14 19:24:39
 */
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class Message extends \app\Home\controller\Base
{
	public function getMessageByUserId(){
		$data = Request::post();

		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
		$map['receive_user'] = $data['user_id'];
		$map['status'] = $data['status'];
		try {
			$message = Db::name('message')->where($map)->order('time','desc')->select();
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}

		return returnData($message,1,'处理成功');
		
	}

	public function updateMessageStatus(){
		$data = Request::post();

		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
		$map['id'] = $data['id'];
		$updateData['status'] = $data['status'];
		try {
			$rst = Db::name('message')->where($map)->setField($updateData);
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		if($data['status']==1){
			return returnData(null,1,'');
		}else{
			return returnData(null,1,'处理成功');
		}
	}
}
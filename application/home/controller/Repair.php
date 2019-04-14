<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 14:10:48
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-04-13 22:09:57
 */
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class Repair extends \app\Home\controller\Base
{
	public function getRepairList($user_id=''){
		$data=Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
		try {
			$process = implode(",", $data['process']);
			// $hasAdmin = array_key_exists('admin_id',$data);
			$hasLevel = array_key_exists('admin_level',$data);
			// 只给非超管限制admin_id
			if($hasLevel){
				if($data['admin_level']!=2){
					$map['admin_id'] = $data['admin_id'];
				}
			}
			$hasWorker = array_key_exists('worker_id',$data);
			if($hasWorker){
				$map['worker_id'] = $data['worker_id'];
			}
			$pageSize = $data['pageSize'];
			// $map['user_id']  = array('in',$data['user_id']);
			$area = '%'.$data['area'].'%';
			// $map['process'] = array('in','1,2,3,4');
			$map['process'] = $data['process'];
 			$data = Db::name('service')->where($map)->where('area','like',$area)->limit($pageSize)->order('date','desc')->failException(false)->select();  //为空不报错
		} catch (Exception $e) {
			return returnData('error',-1,$e->getMessage());
		}
		return returnData($data,1,'处理成功');
	}
}
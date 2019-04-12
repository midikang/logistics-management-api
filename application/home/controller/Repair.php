<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 14:10:48
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-03-24 20:34:23
 */
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Validate;
use think\Loader;
class Repair extends \app\Home\controller\Base
{
	public function getRepairList(){
		$data=Request::post();
		if(count($data)==0){
			return returnData('error',-1,'[参数错误]');
		}
		try {
			$process = implode(",", $data['process']);
			// echo $process;
			// exit();
			$admin_id = $data['admin_id'];
			if($admin_id!=''){
				$map['admin_id'] = $admin_id;
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
<?php

/**
 * @Author: wanwan
 * @Date:   2019-01-08 14:28:03
 * @Last Modified by:   wanwan
 * @Last Modified time: 2019-01-09 17:51:36
 */
namespace app\admin\controller;
use think\Db;
use think\facade\Request;

class Cate
{
	public function index()
	{ 
		$isPost=Request::isPost();
		if($isPost==false){
			return returnData('error');
		}else{
			$data=Db::name('terms')->select();
			if(is_array($data) && count($data) > 0)
	        {
	            $data = Tree::makeTreeForHtml($data);
	            foreach($data as $key => $val){
	                $data[$key]['level'] = str_repeat('&#12288;',$val['level']);
	            }
	        }
			return returnData($data);
		}
	}

	public function getSubCate(){
		$has=Request::has('parent_id','post');
		if(!$has){
			return returnData('error');
		}else{
			$parent_id=Request::param('parent_id');
			$res=Db::name('terms')->where('parent_id',$parent_id)->select();
			return returnData($res);
		}
		
	}

}
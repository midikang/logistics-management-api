<?php

/**
 * @Author: wanwan
 * @Date:   2019-01-08 14:28:03
 * @Last Modified by:   wanwan
 * @Last Modified time: 2019-01-09 17:07:52
 */
namespace app\admin\controller;
use think\Db;
use think\facade\Request;

class Menu
{
	public function index()
	{ 
		$isPost=Request::isPost();
		if($isPost==false){
			return returnData('error');
		}else{
			$cid=4;
			$data = Db::name('nav')->where('cid',$cid)->field('id,parent_id,label,status,listorder')->order('listorder')->select();
			if(is_array($data) && count($data) > 0)
	        {
	            $data = Tree::makeTreeForHtml($data);
	            foreach($data as $key => $val){
	                $data[$key]['level'] = str_repeat('&#12288;',$val['level']);
	            }
	        }
			// $res=Db::name('nav')->where('cid',4)->select();
			return returnData($data);
		}
	}

}
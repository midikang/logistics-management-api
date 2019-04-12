<?php

/**
 * @Author: wanwan
 * @Date:   2019-01-09 17:58:55
 * @Last Modified by:   wanwan
 * @Last Modified time: 2019-01-10 11:26:16
 */
namespace app\admin\controller;
use think\Db;
use think\facade\Request;
class Article extends \app\admin\controller\Base
{
	protected function initialize()
    {
    	
    	
    }

	public function getArticle(){
			$isPost=Request::isPost();
			if($isPost==false){
				return returnData('error');
			}
			$data=Db::name('posts')->failException()->select();
			return returnData($data);
		
	}
}
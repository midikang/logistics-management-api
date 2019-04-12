<?php

/**
 * @Author: wanwan
 * @Date:   2019-01-07 14:39:07
 * @Last Modified by:   wanwan
 * @Last Modified time: 2019-01-08 14:36:45
 */
namespace app\admin\controller;
use think\Db;


class Login
{
    public function index()
    {
    	// $res=Db::name('nav')->where('status',1)->select();
        $res='lala'; 
    	return json($res);
    	// var_dump($res);
        // return view();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
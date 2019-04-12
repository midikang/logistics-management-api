<?php
namespace app\home\controller;
use think\Db;
class Nav
{
    public function index()
    {
    	$res=Db::name('nav')->where('status',1)->select();
    	return json($res);
    	// var_dump($res);
        // return view();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}

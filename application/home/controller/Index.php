<?php
namespace app\home\controller;
use think\Db;
class Index
{
    public function index()
    {
    	// $res=Db::name('nav')->where('status',1)->select();
    	// return json($res);
    	// var_dump($res);
        return view('index',['name'=>'thinkphp']);
        // return view();
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function doLogin(){
        echo 'kk';
    }
}

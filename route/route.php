<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

// Route::get('admin', 'admin/Login/index');

Route::get('hello/:name', 'index/hello');

Route::get('api','home/Nav/index');

Route::get('login','admin/Login/index');

// Route::get('getSubCate','admin/Cate/getSubCate')->cache(3600);

return [

];

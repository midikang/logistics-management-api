<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 15:45:10
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-03-24 16:49:44
 */
namespace app\home\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule =   [
        'tel'  => 'require|max:11|mobile|unique:admin',
        'area' => 'require',
        'name' => 'require',
        'level' => 'require',
        'password' => 'alphaDash'
    ];
    
    protected $message  =   [
        'tel.require' => '联系电话不能为空',
        'tel.max' => '手机号不合法',
        'tel.mobile' => '手机号不合法',
        'tel.unique' => '手机号已存在',
        'name.require' => '姓名不能为空',
        'name.unique' => '管理员已存在',
        'area.require' => '区域不能为空',
        'level.require' => '权限不能为空',
        'password.alphaDash' => '密码不合法'

    ];
    
}
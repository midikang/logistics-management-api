<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 15:45:10
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-03-24 16:47:50
 */
namespace app\home\validate;

use think\Validate;

class Worker extends Validate
{
    protected $rule =   [
        'tel'  => 'require|max:11|mobile|unique:worker',
        'area' => 'require',
        'name' => 'require|unique:worker',
        'type' => 'require',
    ];
    
    protected $message  =   [
        'tel.require' => '联系电话不能为空',
        'tel.max' => '手机号不合法',
        'tel.mobile' => '手机号不合法',
        'tel.unique' => '手机号已存在',
        'name.require' => '姓名不能为空',
        'name.unique' => '维修工已存在',
        'area.require' => '所属区域不能为空',
        'type.require' => '维修范围不能为空',

    ];
    
}
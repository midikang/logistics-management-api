<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 15:45:10
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-03-15 22:38:42
 */
namespace app\home\validate;

use think\Validate;

class Service extends Validate
{
    protected $rule =   [
        'tel'  => 'require|max:11',
        'area' => 'require',
        'area_detail' => 'require',
        'details' => 'require',
        'type' => 'require',
        'user_id' => 'require'
        
    ];
    
    protected $message  =   [
        'tel.require' => '联系方式不能为空',
        'area.require' => '区域不能为空',
        'area_detail' => '详细地址不能为空',
        'details.require' => '故障描述不能为空',
        'type.require' => '故障类型不能为空',
        'user_id' => '未获取到用户信息'

    ];
    
}
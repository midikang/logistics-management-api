<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 15:45:10
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-03-16 22:56:23
 */
namespace app\home\validate;

use think\Validate;

class User extends Validate
{
    protected $rule =   [
        'student_id'  => 'require|max:11',
        'student_pwd' => 'require',
        'student_name' => 'max:6'
        
    ];
    
    protected $message  =   [
        'student_id.require' => '学号不能为空',
        'student_pwd.require' => '密码不能为空',
        'student_name'  => '姓名过长'

    ];
    
}
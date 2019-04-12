<?php

/**
 * @Author: shiwan
 * @Date:   2019-03-15 15:45:10
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-03-24 18:36:10
 */
namespace app\home\validate;

use think\Validate;

class Process extends Validate
{
    protected $rule =   [
        'admin_id'  => 'require',
        'admin_name'  => 'require',
        'worker_id'  => 'require',
        'worker_name'  => 'require',
        'service_id'  => 'require',
        
        
    ];
    
    protected $message  =   [
        // 'admin_id:require' => ''

    ];
    
}
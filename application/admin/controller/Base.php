<?php

/**
 * @Author: wanwan
 * @Date:   2019-01-10 10:13:43
 * @Last Modified by:   wanwan
 * @Last Modified time: 2019-01-10 10:15:23
 */

namespace app\admin\controller;
class Base extends \think\Controller
{
  protected $siteName = 'PHP中文网';
  protected function test()
  {
    return '欢迎来到'.$this->siteName.'学习thinkphp5开发技术';
  }
}

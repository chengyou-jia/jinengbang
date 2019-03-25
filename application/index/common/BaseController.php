<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 10:30
 */

namespace app\index\common;


use think\Controller;

class BaseController extends Controller
{
    protected $beforeActionList = [
        'is_login',
    ];

    protected function is_login()
    {

    }

}
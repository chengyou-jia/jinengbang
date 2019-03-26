<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 21:22
 */

namespace app\index\validate;


use think\Validate;

class Label extends Validate
{
    protected $rule = [
        'type|类型' => 'require',// todo 类型验证
        'description' => 'require'
    ];

    protected $message = [

    ];

    protected $scene = [
        'update'  =>  ['description']

    ];


}
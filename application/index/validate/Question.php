<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/28
 * Time: 14:01
 */

namespace app\index\validate;


use think\Validate;

class Question extends Validate
{
    protected $rule = [
        'content' => 'require',
        'type' => 'require',// todo 类型验证
    ];

    protected $message = [

    ];

    protected $scene = [

    ];


}
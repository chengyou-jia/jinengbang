<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/26
 * Time: 21:40
 */

namespace app\index\validate;


use think\Validate;

class Help extends Validate
{
    protected $rule = [
        'title' => 'require',
        'content' => 'require',
        'askfor_type' => 'require|in:0,1,2,3,4',// todo 类型验证
        'is_free' => 'require|in:0,1,2,3',
        'type' => 'require|in:0,1',
    ];

    protected $message = [

    ];

    protected $scene = [

    ];

}
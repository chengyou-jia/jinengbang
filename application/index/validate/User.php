<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 10:54
 */

namespace app\index\validate;


use think\Validate;

class User extends Validate
{
    protected $rule = [
        'user_name|用户名' => 'require|max:25',
        'nickname|昵称' => 'require|max:25',
        'wechat|微信号' => 'require',
        'mobile|手机号' => 'require|number|length:6,11',
        'wechat_id|微信id' => 'require'
    ];

    protected $message = [

    ];

    protected $scene = [

    ];


}
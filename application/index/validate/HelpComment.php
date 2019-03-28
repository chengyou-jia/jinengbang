<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/27
 * Time: 10:53
 */

namespace app\index\validate;


use think\Validate;

class HelpComment extends Validate
{
    protected $rule = [
        'content' => 'require',
        'help_id' => 'require',
        'help_comment_id' => 'length:0' //todo 此处验证
    ];

    protected $message = [

    ];

    protected $scene = [
        'update'  =>  ['description']

    ];

}
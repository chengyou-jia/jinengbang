<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/28
 * Time: 15:25
 */

namespace app\index\validate;


use think\Validate;

class QuestionComment extends Validate
{
    protected $rule = [
        'content' => 'require',
        'question_id' => 'require'
    ];

    protected $message = [

    ];

    protected $scene = [

    ];

}
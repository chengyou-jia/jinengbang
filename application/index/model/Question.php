<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/28
 * Time: 14:00
 */

namespace app\index\model;


use app\index\common\BaseModel;

class Question extends BaseModel
{
    protected $table = 'question';
    protected $pk  = 'question_id';

    public function user()
    {
        return $this->belongsTo('User');
    }

}
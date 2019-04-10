<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/4/10
 * Time: 14:55
 */

namespace app\index\model;


use app\index\common\BaseModel;

class Suggestion extends BaseModel
{
    protected $table = 'suggestion';
    protected $pk = 'suggestion_id';

    public function user()
    {
        return $this->belongsTo('User');
    }

}
<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 21:21
 */

namespace app\index\model;


use app\index\common\BaseModel;

class Label extends BaseModel
{
    protected $table = 'label';
    protected $pk = 'label_id';

    public function user()
    {
        return $this->belongsTo('User');
    }


}
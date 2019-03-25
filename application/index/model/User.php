<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 10:53
 */

namespace app\index\model;


use app\index\common\BaseModel;

class User extends BaseModel
{
    protected $table = 'user';
    protected $pk = 'user_id';

    public function labels()
    {
        return $this->hasMany('Label','user_id','user_id');
    }

    public function isHadLabelType($type)
    {
        $result = $this->labels()->where('type',$type)->find();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
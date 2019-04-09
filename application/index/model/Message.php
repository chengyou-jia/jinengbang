<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/4/9
 * Time: 18:16
 */

namespace app\index\model;


use app\index\common\BaseModel;

class Message extends BaseModel
{
    protected $table = 'message';
    protected $pk = 'message_id';

    public function user()
    {
        return $this->belongsTo('User');
    }

    static public function addContent($content,$user_id,$is_office=1)
    {
        $user = User::get($user_id);
        $result = $user->messages()->save(
            ['content'=>$content],
            ['is_office'=>$is_office]
        );
        if ($result){
            return true;
        } else {
            return false;
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/4/9
 * Time: 16:24
 */

namespace app\index\model;


use app\index\common\BaseModel;
use think\Db;

class Apply extends BaseModel
{
    protected $table = 'apply';
    protected $pk = 'apply_id';

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function help()
    {
        return $this->belongsTo('Help');
    }

    //增加报名 求助报名加一 新增消息
    // 这样写真tm好恶心
    public function addApply($help_id)
    {
        // 启动事务
        Db::startTrans();
        try {
            $user_id = session('user.user_id');
            $user = User::get($user_id);
            $result1 = $user->applys()->save(['help_id' => $help_id]);
            $help = Help::get($help_id);
            $help_user_id = $help->user_id;
            $help->apply_num = $help->apply_num + 1;
            $result2 = $help->save();
            $content = '你的求助有人报名了，快去查看吧';
            $result3 = Message::addContent($content,$help_user_id);
            $result4 = User::hasNewMessage($help_user_id);
            if ($result1 and $result2 and $result3 and $result4 ) {
                // 提交事务
                Db::commit();
                return true;
            } else {
                // 回滚事务
                Db::rollback();
                return false;
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
        }
    }


}
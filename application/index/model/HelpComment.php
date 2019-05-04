<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/27
 * Time: 10:52
 */

namespace app\index\model;


use app\index\common\BaseModel;
use think\Db;

class HelpComment extends BaseModel
{
    protected $table = 'help_comment';
    protected $pk = 'help_comment_id';

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function help()
    {
        return $this->belongsTo('Help');
    }

    public function saveComment($data,$prior)
    {
        // 启动事务
        Db::startTrans();
        try {
            $help_id = $data['help_id'];
            $user_id = session('user.user_id');
            $user = User::get($user_id);
            $result1 = $user->helpComments()->save([
                'content' =>$data['content'], 'prior' => $prior,'help_id'=>$data['help_id']
            ]);

            //求助回复加一
            $help = help::get($help_id);
            $help->reply = $help->reply + 1;
            $result2 = $help->save();

            //添加新消息
            if ($help->user_id == $user_id) {
                //自己回复无需添加消息
                $result3 = true;
                $result4 = true;
            } else {
                $user_id = $help->user_id;
                if ($prior == -1) {
                    //求助者消息
                    $content = '你的求助有新的评论，快去看看吧';
                    $type = 1;
                    $type_id = $help_id;
                    $result3 = Message::addContent($content,$user_id,$type,$type_id);
                    $user_id = $help->user_id;
                    $result4 = User::hasNewMessage($user_id);
                } else {
                    $content = '你的求助评论被回复了，快去看看吧';
                    $type = 2;
                    $type_id = $prior;
                    $help_comment = HelpComment::get($prior);
                    $user_id = $help_comment->user_id;
                    $result3 = Message::addContent($content,$user_id,$type,$type_id);
                    $result4 = User::hasNewMessage($user_id);
                }
            }


            // 提交事务
            if ($result1 and $result2 and $result3 and $result4) {
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

    static public function deleteWithPrior($help_comment_id)
    {
        // 启动事务
        Db::startTrans();
        try {
            self::destroy($help_comment_id);
            self::destroy(['prior' => $help_comment_id]);
            // 提交事务
            Db::commit();
            return true;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
        }

    }

}
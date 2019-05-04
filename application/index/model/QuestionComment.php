<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/28
 * Time: 15:24
 */

namespace app\index\model;


use app\index\common\BaseModel;
use think\Db;

class QuestionComment extends BaseModel
{
    protected $table = 'question_comment';
    protected $pk = 'question_comment_id';

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function question()
    {
        return $this->belongsTo('Question');
    }

    public function saveComment($data, $prior)
    {
        // 启动事务
        Db::startTrans();
        try {
            $question_id = $data['question_id'];
            $user_id = session('user.user_id');
            $user = User::get($user_id);
            $result1 = $user->questionComments()->save([
                'content' => $data['content'], 'prior' => $prior, 'question_id' => $data['question_id']
            ]);
            //提问回复加一
            $question = Question::get($question_id);
            $question->reply = $question->reply + 1;
            $result2 = $question->save();

            //添加新消息 
            if ($question->user_id == $user_id) {
                //自己回复无需添加消息
                $result3 = true;
                $result4 = true;
            } else {
                $user_id = $question->user_id;
                if ($prior == -1) {
                    //求助者消息
                    $content = '你的提问有新的评论，快去看看吧';
                    $type = 3;
                    $type_id = $question_id;
                    $result3 = Message::addContent($content, $user_id, $type, $type_id);
                    $result4 = User::hasNewMessage($user_id);
                } else {
                    $content = '你的提问评论被回复了，快去看看吧';
                    $type = 4;
                    $type_id = $prior;
                    $question_comment = questionComment::get($prior);
                    $user_id = $question_comment->user_id;
                    $result3 = Message::addContent($content, $user_id, $type, $type_id);
                    $result4 = User::hasNewMessage($user_id);
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
            }
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return false;
        }
    }


    static public function deleteWithPrior($question_comment_id)
    {
        // 启动事务
        Db::startTrans();
        try {
            self::destroy($question_comment_id);
            self::destroy(['prior' => $question_comment_id]);
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
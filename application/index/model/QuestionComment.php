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

    public function saveComment($data,$prior)
    {
        $user_id = session('user.user_id');
        $user = User::get($user_id);
        $user->questionComments()->save([
            'content' =>$data['content'], 'prior' => $prior,'question_id'=>$data['question_id']
        ]);
    }

}
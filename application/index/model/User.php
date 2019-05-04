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
use think\Db;

class User extends BaseModel
{
    protected $table = 'user';
    protected $pk = 'user_id';

    public function labels()
    {
        return $this->hasMany('Label','user_id','user_id');
    }

    public function helps()
    {
        return $this->hasMany('Help','user_id','user_id');
    }

    public function applys()
    {
        return $this->hasMany('Apply','user_id','user_id');
    }

    public function questions()
    {
        return $this->hasMany('Question','user_id','user_id');
    }

    public function helpComments()
    {
        return $this->hasMany('HelpComment','user_id','user_id');
    }

    public function questionComments()
    {
        return $this->hasMany('QuestionComment','user_id','user_id');
    }

    public function messages()
    {
        return $this->hasMany('Message','user_id','user_id');
    }

    public function suggestions()
    {
        return $this->hasMany('Suggestion','user_id','user_id');
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

    //是否已经报名此报名
    public function is_apply($help_id)
    {
        $user_id = session('user.user_id');
        $user = User::get($user_id);
        $apply = $user->applys()->where('help_id',$help_id)->find();
        if ($apply) {
            return true;
        } else {
            return false;
        }
    }

    //是否有此求助
    public function has_help($help_id)
    {
        $user_id = session('user.user_id');
        $user = User::get($user_id);
        $help = $user->helps()->where('help_id',$help_id)->find();
        if ($help) {
            return true;
        } else {
            return false;
        }
    }

    //用户有新消息
    static public function hasNewMessage($user_id)
    {
        $user = User::get($user_id);
        if ($user->new_message == 0) {
            $user->new_message = 1;
            $result = $user->save();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    static public function noNewMessage($user_id)
    {
        $user = User::get($user_id);
        if ($user->new_message == 1) {
            $user->new_message = 0;
            $result = $user->save();
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    // 确认 增加标签积分，改变报名状态，求助状态，消除其他报名状态 改变user转态
    public function applyConfirm($help_id,$apply_id,$score)
    {
        // 启动事务
        Db::startTrans();
        try {
            $help = Help::get($help_id);
            $apply = Apply::get($apply_id);
            $apply_user_id = $apply->user_id;
            $user = User::get($apply_user_id);
            $label_type = $help->askfor_type;
            //改变报名状态,消除其他报名状态
            $apply->status = 1;
            $apply->score = $score;
            $result1 = $apply->save();
            $apply = $help->applys()->where('apply_id','neq',$apply_id)
                ->where('status',0)->find();
            $err = false;
            while ($apply) {
                $apply->status = 2;
                $result = $apply->save();
                if ($result) {
                    $apply = $help->applys()->where('apply_id','neq',$apply_id)
                        ->where('status',0)->find();
                } else {
                    $err = true;
                    break;
                }
            }
            if ($err) {
                $result2 = false;
            } else {
                $result2 = true;
            }

            //改变求助状态
            $help->has_finished = 1;
            $result3 = $help->save();

            //增加标签积分
            $label = $user->labels()->where('type',$label_type)->find();
            $label->score = $label->score + $score;
            $result4 = $label->save();

            //改变user score
            $user->score = $user->score + $score;
            $user->help_num = $user->help_num + 1;
            $result5 = $user->save();

            //new_message
            $content = '你有一个报名被确认，你增加了相应的积分，快去看看吧';
            $type = 5;
            $type_id = $apply_id;
            $result6 = Message::addContent($content,$apply_user_id,$type,$type_id);
            $result7 = User::hasNewMessage($apply_user_id);
            if ($result1 and $result2 and
                $result3 and $result4 and
                $result5 and $result6 and $result7) {
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

    //全部消息设为已读
    public function allRead()
    {
        // 启动事务
        Db::startTrans();
        try {
            $err = false;
            $message = $this->messages()->where('status',0)->find();
            while ($message) {
                $message->status = 1;
                $result = $message->save();
                if ($result) {
                    $message = $this->messages()->where('status',0)->find();
                } else {
                    $err = true;
                    break;
                }
            }
            if ($err) {
                $result = false;
            } else {
                $result = true;
            }

            if ($result) {
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
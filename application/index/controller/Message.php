<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/4/10
 * Time: 18:18
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\User as UserModel;

class Message extends BaseController
{
    public function getAll()
    {
        $status = input('status');
        $user_id = session('user.user_id');

        $user = UserModel::get($user_id);
        if ($status == 'all') {
            $messages = $user->messages()->select();
            if (count($messages)) {
                $data = array('total'=>count($messages),'data'=>$messages);
                return success($data);
            } else {
                return error('没有消息');
            }
        } elseif ($status == 0) {
            $messages = $user->messages()->where('status', 0)->select();
            if (count($messages)) {
                $data = array('total'=>count($messages),'data'=>$messages);
                return success($data);
            } else {
                return error('没有消息');
            }
        } elseif ($status == 1) {
            $messages = $user->messages()->where('status', 1)->select();
            if (count($messages)) {
                $data = array('total'=>count($messages),'data'=>$messages);
                return success($data);
            } else {
                return error('没有消息');
            }
        } else {
            return error('参数错误');
        }
    }

    public function getOne($message_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $message = $user->messages()
            ->where('message_id',$message_id)->find();
        if ($message) {
            if ($message->status == 0) {
                $message->status = 1;
                $result = $message->save();
                if (!$result) {
                    return error('获取错误');
                }
            }
            return success($message);
        } else {
            return error('你没有此消息');
        }
    }

    public function allRead()
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $result = $user->allRead();
        if ($result) {
            return success();
        } else {
            return error('操作失败');
        }

    }


}
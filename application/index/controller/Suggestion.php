<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/4/10
 * Time: 14:58
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\User as UserModel;
use app\index\model\Suggestion as SuggestionModel;

class Suggestion extends BaseController
{
    public function create()
    {
        $content = input('content');
        if (empty($content)) {
            return error('建议不能为空');
        }
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $result = $user->suggestions()->save(['content'=>$content]);
        if ($result) {
            return success();
        } else {
            return error('添加失败');
        }

    }

    public function getAll()
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $suggestions = $user->suggestions()->select();
        if (count($suggestions)) {
            $data = array('total'=>count($suggestions),'data'=>$suggestions);
            return success($data);
        } else {
            return error('没有建议');
        }
    }

    public function getOne($suggestion_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $suggestion = $user->suggestions()
            ->where('suggestion_id',$suggestion_id)->find();
        if ($suggestion) {
            return success($suggestion);
        } else {
            return error('没有此建议');
        }

    }

    public function delete($suggestion_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $suggestion = $user->suggestions()
            ->where('suggestion_id',$suggestion_id)->find();
        if (empty($suggestion)) {
            return error('你没有此求助');
        }
        $result = $suggestion->delete();
        if ($result) {
            return success();
        } else {
            return error('删除失败');
        }
    }

    public function reply($suggestion_id)
    {
        $reply = input('reply');
        $suggestion = SuggestionModel::get($suggestion_id);

        if (empty($reply)) {
            return error('回复内容不能为空');
        }
        if (!is_admin()) {
            return error('没有权限');
        }
        if (empty($suggestion)) {
            return error('该建议不存在');
        }
        if ($suggestion->status == 1) {
            return error('该建议已被回复');
        }
        $suggestion->reply = $reply;
        $suggestion->status = 1;
        $result = $suggestion->save();
        if ($result) {
            return success();
        } else {
            return error('回复失败');
        }

    }


}
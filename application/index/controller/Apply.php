<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/4/9
 * Time: 16:29
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\User as UserModel;
use app\index\model\Help as HelpModel;
use think\Validate;

class Apply extends BaseController
{
    public function create($help_id)
    {
        $content = input('content');
        $user_id = session('user.user_id');
        $user  = UserModel::get($user_id);
        $help = HelpModel::get($help_id);

        // 验证
        if (empty($help)) {
            return error('此求助不存在');
        }
        if ($user->is_apply($help_id)) {
            return error('你已报名此求助');
        }
        if ($help->user_id == $user_id) {
            return error('你不能报名自己的求助');
        }
        if (empty($content)) {
            return error('内容不能为空');
        }
        $label = $user->labels()->where('type',$help->askfor_type)->find();
        if (empty($label)) {
            $result = $user->labels()->save(['type'=>$help->askfor_type]);
            if (!$result) {
                return error('创建标签失败');
            }
        }
        //处理
        $apply = model('Apply');
        $result = $apply->addApply($help_id,$content);
        if ($result) {
            return success();
        } else {
            return error('报名失败');
        }
    }

    public function getApplyForHelp($help_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $help = HelpModel::get($help_id);

        //验证
        if (empty($help)) {
            return error('此求助不存在');
        }
        if (!$user->has_help($help_id)) {
            return error('你没有此求助');
        }

        //处理
        $apply = $help->applys()->order('create_time','desc')->select();
        if (count($apply)) {
            $apply = $apply->toArray();
            for ($i = 0; $i < count($apply); $i++) {
                $apply[$i] = addUserNickname($apply[$i]);
            }
            return success($apply);
        } else {
            return error('此求助没有人报名');
        }
    }

    public function getMyApply()
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $apply = $user->applys()->order('create_time','desc')->select();
        if (count($apply)) {
            return success($apply);
        } else {
            return error('你没有报名');
        }

    }

    public function delete($apply_id)
    {
        $user_id  = session('user.user_id');
        $user = UserModel::get($user_id);
        $apply = $user->applys()->where('apply_id',$apply_id)->find();
        //验证
        if (empty($apply)) {
            return error('你没有此报名');
        }
        if ($apply->status != 0) {
            return error('此报名不能取消');
        }
        //处理
        $result = $apply->delete();
        if ($result) {
            return success();
        } else {
            return error('取消失败');
        }
    }

    public function confirm($help_id)
    {
        $score = input('score');
        $num = array(0,1,2,3,4,5);
        if (!in_array($score,$num)) {
            return error('分数不符合要求');
        }
        $apply_id = input('apply_id');
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $help = $user->helps()->where('help_id',$help_id)->find();
        if (empty($help)) {
            return error('没有此求助');
        }
        $apply = $help->applys()->where('apply_id',$apply_id)->find();
        if (empty($apply)) {
            return error('没有此报名');
        }
        // 处理 这个处理会很难写
        $result = $user->applyConfirm($help_id,$apply_id,$score);
        if ($result) {
            return success();
        } else {
            return error();
        }

    }

    public function isApply($help_id)
    {
        $user_id = session('user.user_id');
        $user  = UserModel::get($user_id);
        if ($user->is_apply($help_id)) {
            $is_apply = true;
            return success($is_apply);
        } else {
            $is_apply = false;
            return success($is_apply);
        }
    }

}
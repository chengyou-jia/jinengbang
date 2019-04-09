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

class Apply extends BaseController
{
    public function create($help_id)
    {

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
        //处理
        $apply = model('Apply');
        $result = $apply->addApply($help_id);
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
        $apply = $help->applys()->select();
        if (count($apply)) {
            return success($apply);
        } else {
            return error('此求助没有人报名');
        }
    }

    public function getMyApply()
    {

    }

    public function delete($apply_id)
    {

    }

}
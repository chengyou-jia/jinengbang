<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/26
 * Time: 21:39
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\User as UserModel;

class Help extends BaseController
{
    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'Help');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            //存储
            $result = $user->helps()->save([
                'content' => $data['content'],
                'is_free' => $data['is_free'],
                'askfor_type' => $data['askfor_type']
                // todo 图片待存储
            ]);
            if ($result) {
                return success();
            } else {
                return error('新增失败');
            }

        } else {
            return error($checkResult);
        }

    }

    public function update($help_id)
    {
        $data = input('param.');
        $checkResult = validateData($data,'Help');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            //查找
            $help = $user->helps()->where('help_id',$help_id)->find();
            //验证
            if (empty($help)){
                return error('你没有此求助');
            }

            $result = $help->save([
                'content' => $data['content'],
                'is_free' => $data['is_free'],
                'askfor_type' => $data['askfor_type']
                // todo 图片待存储
            ]);
            if ($result) {
                return success();
            } else {
                return error('更新失败');
            }
        } else {
            return error($checkResult);
        }

    }

    public function delete($help_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $help= $user->helps()->where('help_id',$help_id)->find();
        if (empty($help)) {
            return error('你没有此求助');
        } else {
            $result = $help->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        }
    }

    public function getOne($help_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $help = $user->helps()->where('help_id',$help_id)->find();
        if (!empty($help)) {
            return success($help);
        } else {
            return error('你没有标签');
        }
        

    }

    public function getAll()
    {
        //todo 分页
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $helps = $user->helps()->where(true)->select();
        if (count($helps)) {
            return success($helps);
        } else {
            return error('你没有标签');
        }

    }
}
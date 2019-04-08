<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/4/8
 * Time: 10:45
 */

namespace app\index\controller;


use think\Controller;
use app\index\model\User as UserModel;

class Developer extends Controller
{
    public function getAllAdmin()
    {
        if (!is_developer()) {
            return error('没有权限');
        }
        $user = model('User');
        $user = $user->where('role',1)->select();
        $total = count($user);
        if (!$total) {
            $data = array('total'=>$total,'data'=>$user);
            return success($data);
        } else {
            return error('没有内容');
        }
    }

    public function deleteAdmin($user_id)
    {
        $user = UserModel::get($user_id);
        if (!is_developer()) {
            return error('没有权限');
        }
        if (empty($user)) {
            return error('该用户不存在');
        }
        if ($user->role != 1) {
            return error('该用户不是普通管理员');
        }
        // 处理
        $user->role = 0;
        $result = $user->save();
        if ($result) {
            return success();
        } else {
            return error('解除失败');
        }
    }

    public function addAdmin($user_id)
    {
        $user = UserModel::get($user_id);
        if (!is_developer()) {
            return error('没有权限');
        }
        if (empty($user)) {
            return error('该用户不存在');
        }
        if ($user->role != 0) {
            return error('该用户不是普通用户');
        }
        // 处理
        $user->role = 1;
        $result = $user->save();
        if ($result) {
            return success();
        } else {
            return error('添加成功');
        }
    }

    public function getOneAdmin($user_id)
    {
        if (!is_developer()) {
            return error('没有权限');
        }
        $user = model('User');
        $user = $user->where('user_id',$user_id)->where('role',1)->find();
        if (empty($user)) {
            return error('没有找到该用户，或该用户不是管理员');
        } else {
            return success($user);
        }
    }


}
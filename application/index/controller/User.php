<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 10:53
 */

namespace app\index\controller;



use app\index\common\BaseController;
use app\index\model\User as UserModel;

class User extends BaseController
{

    public function login()
    {

    }

    //这玩意不确定要不要写
    public function logout()
    {

    }

    public function getInfo()
    {
        $user = session('user');
        return success($user);
    }

    // todo 关于验证的问题 何时不能注册
    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'User');
       if ($checkResult === true) {
           $user = model('User');
           $result = $user->allowField(true)->save($data);
           $user_id = $user->user_id; //再次查找存session
           if ($result) {
               $user = model('User');
               $user = $user->where('user_id',$user_id)->find();
               session('user',$user);
               return success();
           } else {
               return error('注册失败');
           }
       } else {
           return error($checkResult);
       }

    }

    public function update()
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        //验证
        if (empty($user)) {
            return error('用户不存在');
        }

        $data = input('param.');
        $checkResult = validateData($data,'User');
        if ($checkResult === true) {
            $result = $user->allowField(true)->save($data);
            $user_id = $user->user_id; //再次查找存session
            if ($result) {
                $user = model('User');
                $user = $user->where('user_id',$user_id)->find();
                session('user',$user);
                return success();
            } else {
                return error('更新失败');
            }
        } else {
            return error($checkResult);
        }

    }

    public function delete()
    {

    }

}
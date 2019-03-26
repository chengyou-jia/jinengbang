<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 21:21
 */

namespace app\index\controller;



use app\index\common\BaseController;
use app\index\model\User as UserModel;

class Label extends BaseController
{

    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'Label');
       if ($checkResult === true) {
           $user_id = session('user.user_id');
           $user = UserModel::get($user_id);
           // 验证
           if ($user->isHadLabelType($data['type'])) {
               return error('已有此类型');
           }
           //存储
           //这里allowFiled好坑
           $result = $user->labels()->save(
               ['type'=>$data['type'],'description'=>$data['description']]);
           if ($result) {
               return success();
           } else {
               return error('新增失败');
           }
       } else {
           return error($checkResult);
       }

    }

    public function update($label_id)
    {
        $data = input('param.');
        $checkResult = validateData($data,'Label','update');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            $label= $user->labels()->where('label_id',$label_id)->find();
            if (empty($label)) {
                return error('你没有此标签');
            } else {
                $label->description = $data['description'];
                $result = $label->save();
                if ($result) {
                    return success();
                } else {
                    return error('更新失败');
                }
            }

        } else {
            return error($checkResult);
        }

    }

    public function delete($label_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $label= $user->labels()->where('label_id',$label_id)->find();
        if (empty($label)) {
            return error('你没有此标签');
        } else {
            $result = $label->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        }

    }

    public function getAll()
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $labels = $user->labels()->where(true)->select();
        if (count($labels)) {
            return success($labels);
        } else {
            return error('你没有标签');
        }
    }

    public function getOne($label_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $label = $user->labels()->where('label_id',$label_id)->select();
        if (count($label)) {
            return success($label);
        } else {
            return error('你没有标签');
        }


    }


}
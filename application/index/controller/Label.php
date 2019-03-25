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

    }

    public function delete($label_id)
    {

    }

    public function getAll()
    {

    }

    public function getOne($label_id)
    {

    }


}
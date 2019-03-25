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

class User extends BaseController
{
    public function getInfo()
    {

    }

    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'User');
       if ($checkResult === true) {
           $user = model('User');
           $result = $user->allowField(true)->save($data);
           if ($result) {
               return success();
           } else {
               return error('注册失败');
           }

       } else {
           return error($checkResult);
       }

    }

    public function udapte()
    {

    }

    public function delete()
    {

    }

}
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
use think\db;
class User extends BaseController
{

    public function login()
    {
        // 
        $wechat_id = input('wechat_id');
        $user = model('User');
        //$user = UserModel::get(49);
        //dump($user);
        //$user = new UserModel();
        //$user = $user->where('user_id',49)->find();
        //dump($user);
        //$user = new UserModel();
        $user = $user->where('wechat_id',$wechat_id)->find();
//        $sql = Db::name('user')->getLastSql();
//        dump($sql);
//        $user = $user->where('role',$role)->find();

        //dump($user);
        if ($user) {
            session('user',$user);
            return success();
        } else {
            return error('找不到该用户');
        }
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
           // 验证是否已经注册
           $user = model('User');
           $user = $user->where('wechat_id',$data['wechat_id'])->find();
           if ($user) {
               return error('已经注册');
           }
           $user = model('User');
//           dump($user);
           $result = $user->allowField(true)->save($data);
//           dump($user);
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
            $is_cert = $user->is_cert;
            if ($is_cert) {
                unset($data['user_name']);
                unset($data['gender']);
            }
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

    public function certification()
    {
        //验证
        $is_cert = session('user.is_cert');
        if ($is_cert != 0) {
            return error('当前状态无法提交申请');
        }
        // 上传文件
        $file = request()->file('image');
        if (empty($file)) {
            return error('上传不能为空');
        }
        // 移动到框架应用根目录/uploads/certification 目录下
        $info = $file->move( './uploads/certification');
        if ($info) {
            // 成功上传后 存放上传信息
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            // todo 存储地址可能待定
            $cert_photo = $info->getSaveName();
            $cert_photo = str_replace("\\","/",$cert_photo);
            $cert_photo = 'certification/'.$cert_photo;
            $is_cert = 1;
            //保存
            $user->cert_photo = $cert_photo;
            $user->is_cert = $is_cert;
            $result = $user->save();
            if ($result) {
                return success();
            } else {
                return error('提交失败');
            }

        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }

    }

    public function adminAuditCert($user_id)
    {
        $isPass = input('isPass');
        $user = UserModel::get($user_id);

        //验证
        if ($isPass != 0 and $isPass != 1) {
            return error('参数错误');
        }
        if (empty($user)) {
            return error('该用户不存在');
        }
        if ($user->is_cert != 1) {
            return error('该用户不属于审核状态');
        }

        if ($isPass == 0) {
            $user->is_cert = 0;
            $result = $user->save();
            if ($result) {
                return success();
            } else {
                return error('更改失败');
            }
        } else {
            $user->is_cert = 2;
            $result = $user->save();
            if ($result) {
                return success();
            } else {
                return error('更改失败');
            }
        }


    }

    public function adminGetCert()
    {
        // 验证
        if (!is_admin()) {
            return error('没有权限');
        }
        $user = model('User');
        $user  = $user->where('is_cert',1)->select();
        if (count($user)) {
            return success($user);
        } else {
            return error('没有内容');
        }
    }

    public function getOtherPage($user_id)
    {
        $user = UserModel::get($user_id);
        if (empty($user)) {
            error('该用户不存在');
        }
        $labels = $user->labels()->where(true)->select();
        $user = $user->toArray();
        unset($user['user_name']);
        unset($user['wechat_id']);
        $labels = $labels->toArray();
        $data = array('user'=>$user,'labels'=>$labels);
        return success($data);
    }

    public function changePhoto()
    {
        $photo = input('photo');
        if (empty($photo)) {
            return error('头像不能为空');
        }
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $user->photo = $photo;
        $result = $user->save();
        if ($result) {
            return success();
        } else {
            return error('存储失败');
        }

    }

    public function getOpenid()
    {
        $appid = 'wxef991d2d36f742fc';
        $secret = 'ee17564af9e244e580eb008312109c76';
        $js_code = input('code');
        $grant_type = 'authorization_code';
        $data = array(
            'appid' => $appid,
            'secret' => $secret,
            'js_code' => $js_code,
            'grant_type' => $grant_type
        );
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = send_request($url,$data);
        return $data;
    }

}
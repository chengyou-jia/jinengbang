<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 10:30
 */

namespace app\index\common;


use think\Controller;
use app\index\model\User as UserModel;

class BaseController extends Controller
{
    protected $beforeActionList = [
        'is_login',
    ];

    protected function is_login()
    {

    }

    public function getOneFile($inputName,$saveName)
    {
        // 上传文件
        $result = array('result'=>true,'message'=>'');
        $file = request()->file($inputName);
        if (empty($file)) {
            $result['result'] = false;
            $result['message'] = '上传不能为空';
            return $result;
        }
        // /uploads/certification 目录下
        $info = $file->move( './uploads/'.$saveName);
        if ($info) {
            // 成功上传后 存放上传信息
            // todo 存储地址可能待定
            dump($info->getSaveName());
            $name = $info->getSaveName();
            str_replace('\\','/',$name);
            $avatar = 'uploads/'.$saveName.'/'.$name;
            dump($avatar);
            $result['message'] = $avatar;
            return $result;
            } else {
            // 上传失败获取错误信息
            $result['result'] = false;
            $result['message'] = $file->getError();
        }
    }

    public function getManyFiles($inputName,$saveName)
    {
        // 上传文件
        $result = array('result'=>true,'message'=>null);
        $files = request()->file($inputName);
        if (empty($files)) {
            return $result;
        }
        // /uploads/help 目录下
        $picture = '';
        foreach ($files as $key => $file) {
            $info = $file->move( './uploads/'.$saveName);
            if ($info) {
                dump($info);
                // 成功上传后 存放上传信息
                $picture = $picture.'uploads/'.$saveName.'/'.$info->getSaveName().' | ';
            } else {
                // 上传失败获取错误信息
                $result['result'] = false;
                $result['message'] = $file->getError();
                return $result;
            }
        }
        $result['message'] = $picture;
        return $result;

    }
}
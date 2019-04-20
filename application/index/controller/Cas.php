<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/15 0015
 * Time: 下午 2:44
 */

namespace app\index\controller;




use tiaozhan\api\client\ClientV2;
use app\index\model\User as UserModel;

class Cas
{

    private $client;

    /**
     * @var ClientV2
     */

    public function __construct()
    {
        $this->client = new ClientV2(false);
    }

    public function login()
    {
        // 这里要填写的是，npm所开启的，从浏览器打开就能看到前端热重载页面的域名，跟反向代理无关
        ////获取当前域名
        $frontendOrigin = \think\facade\Env::get('APP_FRONTEND_ORIGIN', 'http://www.jinengbang.com', false);
        $callbackUrl = $frontendOrigin . '/login/callback';

        $redirectUrl = $this->client->casLogin($callbackUrl,true);
        if ($redirectUrl) {
            return success($redirectUrl);
        } else {
            return error();
        }
    }


    public function callback()
    {
        $guid = input('guid');
        $data = $this->client->casLoginCheck($guid);
        if (empty($data['userinfo'])) {
            return error('认证失败');
        }
        // 处理
        $user_name = $data['userinfo']['username'];
        $gender = $data['userinfo']['sex'];
        $user = UserModel::get(session('user.user_id'));
        $genderArr = ['男','女'];
        $code = $user->gender;
        if ($user_name == $user->user_name and $gender == $genderArr[$code]) {
            $user->is_cert = 1;
            $result = $user->save();
            if ($result) {
                return success();
            } else {
                return error('认证失败');
            }
        } else {
            return error('该账号与你的用户名或性别不一致');
        }
    }
}
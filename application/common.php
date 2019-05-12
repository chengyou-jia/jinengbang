<?php

//公共函数模块

/**
 * 将成功后的data包装为json返回
 */
function success($data = null)
{
    if ($data === null) {
        $json = ['success' => 1];
    } else {
        $json = ['success' => 1, 'data' => $data];
    }
    return json($json);
}

/**
 * 将错误提示信息包装为json返回
 */
function error($errMsg = 'ERROR')
{
    return json(['success' => 0, 'err_msg' => $errMsg]);
}

//增加用户姓名
function addUserName($data)
{
    $user_id = $data['user_id'];
    $user = \app\index\model\User::get($user_id);
    $user_name = $user->user_name;
    $data = array('user_name'=>$user_name)+$data;
    return $data;
}

//增加用户昵称
function addUserNickname($data)
{
    $user_id = $data['user_id'];
    $user = \app\index\model\User::get($user_id);
    if (empty($user)) {
        $nickname = '该用户不存在';
        $data = array('nickname'=>$nickname)+$data;
        return $data;
    }
    $nickname = $user->nickname;
    $avatar = $user->photo;
    $is_cert = $user->is_cert;
    $data = array('nickname'=>$nickname,'photo'=>$avatar,'is_cert'=>$is_cert)+$data;
    return $data;
}

// 输入数据验证
function validateData($data,$validate,$scene=null)
{
    $validate = validate($validate);
    $result = $validate->scene($scene)->check($data);
    if ($result) {
        return true;
    } else {
        return $validate->getError();
    }
}

function getOneFile($inputName,$saveName)
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
        $name = str_replace("\\","/",$info->getSaveName());
        $name = $saveName.'/'.$name;

        $result['message'] = $name;
        return $result;
    } else {
        // 上传失败获取错误信息
        $result['result'] = false;
        $result['message'] = $file->getError();
    }
}

// 管理员
function is_admin()
{
    $role = session('user.role');
    if ($role==1 or $role==2) {
        return true;
    } else {
        return false;
    }

}

// 是否是开发者
function is_developer()
{
    $role = session('user.role');
    if ($role==2) {
        return true;
    } else {
        return false;
    }
}


/**
 * 发送HTTP请求
 *
 * @param string $url 请求地址
 * @param string $method 请求方式 GET/POST
 * @param string $refererUrl 请求来源地址
 * @param array $data 发送数据
 * @param string $contentType
 * @param string $timeout
 * @param string $proxy
 * @return boolean
 */
function send_request($url, $data=[], $refererUrl = '', $method = 'GET', $contentType = 'application/json', $timeout = 30, $proxy = false) {
    $ch = null;
    if('POST' === strtoupper($method)) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER,0 );
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($refererUrl) {
            curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
        }
        if($contentType) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
        }
        if(is_string($data)){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    } else if('GET' === strtoupper($method)) {
        if(is_string($data)) {
            $real_url = $url. (strpos($url, '?') === false ? '?' : ''). $data;
        } else {
            $real_url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($data);
        }

        $ch = curl_init($real_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($refererUrl) {
            curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
        }
    } else {
        $args = func_get_args();
        return false;
    }

    if($proxy) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    }
    $ret = curl_exec($ch);
    $info = curl_getinfo($ch);
    $contents = array(
        'httpInfo' => array(
            'send' => $data,
            'url' => $url,
            'ret' => $ret,
            'http' => $info,
        )
    );
    curl_close($ch);
    return $ret;
}


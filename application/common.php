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


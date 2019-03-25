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


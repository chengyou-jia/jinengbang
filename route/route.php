<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

Route::get('/', 'index/index/index');
Route::group('api', function () {
    Route::group('user', function () {
        Route::get('/', 'index/user/getInfo');
        Route::post('/','index/user/create');
        Route::put('/', 'index/user/update');
        Route::delete('/','index/user/delete');
    });
});


return [

];

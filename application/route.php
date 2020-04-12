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

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];
// 引入路由类
use think\Route;
// Route::rule('meeting','index/Meeting/index');
// Route::rule('visit','index/Visitpeople/index');
Route::rule('attend','index/attend/index');
Route::rule('attend1','index/attend/index1');
Route::rule('pull','index/pull_sync/pull');
Route::rule('sync','index/pull_sync/sync');

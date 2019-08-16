<?php
/**
 * User: 周星
 * Date: 2019/3/21
 * Time: 17:33
 * Email: zhouxing@benbang.com
 */

return [
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,

    //--------------------------------------业务配置----------------------------------//
    'login_app' => 1, // app token
    'login_xcx' => 2, // 小程序token
    'login_xcx_expire' => 3600, // 小程序token有效期

    //--------------------------------------user相关----------------------------------//
    // user 状态
    'user_hide' => 0, // 禁用
    'user_show' => 1, // 启用
    'user_del' => 2, // 删除
];
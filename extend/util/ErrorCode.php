<?php
namespace util;

/**
错误码为5位
第一位为version code，默认为1
第2，3位为模块编号，通用的错误码的2，3位为00，其他模块依次递增
第4，5位为此模块下的错误码编号，从01开始递增
**/
class ErrorCode {
    // 100. 通用错误码，系统级别错误
    const UNKNOWN = 10001; // 未知错误
    const PARAMS_ERROR = 10002; // 请求参数错误
    const SERVER_ERROR = 10003; // 服务器错误
    const UNAUTHORIZED = 10004; // 用户未认证
    const FORBIDDEN = 1005; // 用户无权限访问该资源
    const NO_DATA = 10006; // 没有数据了
    const DEPRECATED = 10007; // 此接口已废弃，请升级到新版本客户端
    const NOT_READY = 10008; // 正在建设中
    const NOT_FOUND = 10009; // 资源不存在
    const ADD_FAILED = 10010; // 添加失败
    const DELETE_FAILED = 10011; // 删除失败
    const UPDATE_FAILED = 10012; // 更新失败
    const SELECT_FAILED = 10013; // 查找失败
    const INVALID_CONFIG = 10014; // 配置错误
    const DATA_REPEATED_ERROR = 10015; // 数据重复的出现的错误

    // 101 应用中心、token 申请、授权、验证等
    const TOKEN_VERIFY_FAILED = 10101; // 验证失败
    const TOKEN_GENERATE_FAILED = 10102; // 生成失败
    const TOKEN_NOT_FOUND = 10103; // 未找到
    const TOKEN_SAVE_FAILED = 10104; // 保存失败
    const TOKEN_UPDATE_FAILED = 10105; // 更新失败
    const TOKEN_REFRESH_FAILED = 10106; // 刷新失败
    const TOKEN_REVOKE_FAILED = 10107; // 吊销token失败
    const TOKEN_EXPIRED = 10108; // token过期
    const TOKEN_INVALID_ACCESS_TOKEN = 10109; // token不正确
    const TOKEN_INVALID_SERVICE_TOKEN = 10110;
    const TOKEN_INVALID_SIGN_TOKEN = 10111;
    const TOKEN_INVALID_USER_TOKEN = 10112;
    const TOKEN_INVALID_CLIENT_TOKEN = 10113;
    const TOKEN_INVALID_REFRESH_TOKEN = 10114;
    const TOKEN_INVALID_CSRF_TOKEN = 10115;
    const ACTION_TOO_FREQUENTLY = 10116; // 操作太过频繁
    

    // 102. 用户相关
    const MOBILE_EXISTS = 10201; // 此手机号已经注册过了
    const USER_NOT_FOUND = 10202; // 用户不存在
    const USER_EXISTS = 10203; // 用户已存在
    const PASSWORD_WRONG = 10204; // 密码错误
    const PASSWORD_EMPTY = 10205; // 密码不能为空
    const PASSWORD_TOO_SHORT = 10206; // 密码长度不能少于6位
    const VERIFY_CODE_WRONG = 10207; // 验证码错误
    const VERIFY_REQUEST_TOO_FREQUENTLY = 10208; // 验证码发送频繁
    const INVALID_MOBILE = 10213; //无效的电话
    const INVALID_EMAIL = 10214; //无效的电子邮件
    const FAIL_ATTENTION = 10216; //关注失败
    const INVALID_LOG_IN = 10217; //用户未登陆
    const INVALID_POSTCODE = 10218; //无效的邮编
    const USER_BAN = 10219; // 用户被禁用
    const USER_NOT_LOGIN = 10220; // 用户未登录
    const FAIL_SEND_SMS = 10221; // 返送失败
    const SMS_CODE_LOSE_EFFICACY = 10222; // 短信验证码失效

    // 103 104 后台使用

    // 105 之后业务相关
}

<?php
/**
 * User: 周星
 * Date: 2019/3/25
 * Time: 16:58
 * Email: zhouxing@benbang.com
 */

namespace app\api\controller;

use think\Controller;
use util\ErrorCode;
use util\ReqResp;

class Base extends controller {

    protected $userId;
    protected $loginType;

    public function _initialize () {
        parent::initialize();
        try {
            // 验证环境
            $this->checkSource();
            // 验证用户token
            $this->verifyUser();
        } catch (\Exception $e) {
            ReqResp::outputFail($e);
        }

    }

    protected function checkSource () {
        $this->isWx();
    }

    private function isWx() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (false === strpos($user_agent, 'MicroMessenger')) {
            trace('Sources of illegal requests ; ip :' . get_client_ip(), 'error');
            ReqResp::outputSuccess();
        }
    }

    public function _empty () {
        $e = new \Exception("invalid request method", ErrorCode::NOT_FOUND);
        ReqResp::outputFail($e);
    }

    // 获取用户id
    public function getUserId () {
        return $this->userId;
    }

    // 获取登陆类型
    public function getLoginType () {
        return $this->loginType;
    }

    // 验证用户
    public function verifyUser () {
        $userToken = trim(input('user_token'));
        if (empty($userToken)) {
            throw new \Exception("user_token is empty", ErrorCode::TOKEN_VERIFY_FAILED);
        }

        $token = app('UserTokenModel',true)->verifyUserToken($userToken, false);

        $this->userId = $token['user_id'];
        $this->loginType = $token['login_type'];
    }
}
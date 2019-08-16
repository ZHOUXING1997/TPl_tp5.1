<?php
/**
 * User: 周星
 * Date: 2019/3/25
 * Time: 20:45
 * Email: zhouxing@benbang.com
 */

namespace app\api\controller;

use util\ReqResp;

class UnLoginBase extends Base {

    protected $userId;
    protected $openid;

    public function initialize () {
        // parent::initialize(); // TODO: Change the autogenerated stub
    }

    // 获取用户id
    public function getUserId() {
        try {
            $this->verifyUser();
            return $this->userId;
        } catch (\Exception $e) {
            ReqResp::outputFail($e);
        }
    }
}
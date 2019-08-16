<?php
/**
 * User: 周星
 * Date: 2019/3/22
 * Time: 15:02
 * Email: zhouxing@benbang.com
 */

namespace app\common\controller;

use think\Controller;

class AdminBase extends Controller {

    protected $middleware = [];

    public function initialize () {
        $this->middleware = [
            'auth'
        ];
    }
}
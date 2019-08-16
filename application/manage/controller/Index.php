<?php
/**
 * User: 周星
 * Date: 2019/3/22
 * Time: 20:21
 * Email: zhouxing@benbang.com
 */

namespace app\manage\controller;

use app\common\controller\AdminBase;

class Index extends AdminBase {

    public function initialize () {
        parent::initialize();
    }

    public function index () {
        return $this->fetch();
    }

    public function welcome () {
        return $this->fetch();
    }
}
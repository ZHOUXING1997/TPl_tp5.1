<?php
/**
 * User: 周星
 * Date: 2019/5/10
 * Time: 15:59
 * Email: zhouxing@benbang.com
 */

namespace app\manage\controller;

use app\common\controller\AdminBase;

class City extends AdminBase {

    public function index () {
        return $this->fetch();
    }
}
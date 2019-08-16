<?php
/**
 * User: 周星
 * Date: 2019/5/10
 * Time: 15:51
 * Email: zhouxing@benbang.com
 */

namespace app\manage\controller;

use app\common\controller\AdminBase;

// 统计图
class Echarts extends AdminBase {

    public function _empty ($name) {
        return $this->fetch($name);
    }
}
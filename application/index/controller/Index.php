<?php

namespace app\index\controller;

use think\captcha\Captcha;
use think\Controller;

class Index extends Controller {

    public function index () {
        echo "<div style=\"text-align: center;position: absolute; left: 30%;top: 30%;\">
        <label for=\"technical-support\" style=\"display: block;margin-bottom: 200px\"><h1>欢迎使用</h1></label>
        <b id=\"technical-support\"></b>
    </div>";

    }

    public function check () {

    }
}

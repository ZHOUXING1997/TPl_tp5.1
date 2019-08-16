<?php
/**
 * User: 周星
 * Date: 2019/3/22
 * Time: 14:38
 * Email: zhouxing@benbang.com
 */

namespace app\manage\controller;

use think\Controller;
use think\Db;
use think\facade\Cache;
use think\facade\Session;
use think\Request;
use util\ErrorCode;
use util\ReqResp;
use util\CipherSalt;

class Login extends Controller {

    public function initialize () {
        parent::initialize();
    }

    public function login (Request $request) {
        if (!empty(session('ADMIN_ID'))) {
            $this->redirect(url("manage/index/index"));
        }
        return $this->fetch();
    }

    public function switchUser () {
        return view('login/login');
    }

    public function doLogin() {
        try {
            $username = $this->request->param('username');
            if (empty($username)) {
                // $this->loginError('用户名不能为空');
                throw new \Exception('用户名不能为空', ErrorCode::PARAMS_ERROR);
            }

            $password = $this->request->param('password');
            if (empty($password)) {
                // $this->loginError('密码不能为空');
                throw new \Exception('密码不能为空', ErrorCode::PARAMS_ERROR);
            }

            $captcha = $this->request->param('captcha');
            if (empty($captcha)) {
                // $this->loginError(lang('CAPTCHA_REQUIRED'));
                throw new \Exception('验证码不能为空', ErrorCode::PARAMS_ERROR);
            }

            // 验证码
            if (!cmf_captcha_check($captcha)) {
                // $this->error(lang('CAPTCHA_NOT_RIGHT'));
                throw new \Exception('验证码错误', ErrorCode::PARAMS_ERROR);
            }
            $loginTimes = $this->getLoginTimes();
            if ($loginTimes > 5) {
                // $this->loginError('您已经尝试5次,请5分钟后再试');
                throw new \Exception('您已经尝试5次,请5分钟后再试', ErrorCode::USER_BAN);
            }

            $user['user_type'] = 1;
            $user['user_login'] = $username;
            $result = Db::name('admin_user')->where($user)->find();
            if(empty($result)){
                $this->setLoginTimes();
                // $this->loginError('用户不存在');
                throw new \Exception('用户不存在', ErrorCode::USER_NOT_FOUND);
            }
            $password = CipherSalt::encryptPassword($result['salt'], $password);
            if($password != $result['user_pass']){
                $this->setLoginTimes();
                // $this->loginError('密码不正确');
                throw new \Exception('密码不正确', ErrorCode::PASSWORD_EMPTY);
            }
            // 查看当前登录用户有没有对应角色信息，超级管理员除外，并且查看当前用户的状态
            $groups = Db::name('AdminRoleUser')
                ->alias("a")
                ->join('__ADMIN_ROLE__ b', 'a.role_id =b.id')
                ->where(["user_id" => $result["id"], "status" => 1])
                ->value("role_id");
            if ($result["id"] != 1 && (empty($groups) || empty($result['user_status']))) {
                // $this->loginError('该用户已被禁用');
                throw new \Exception('用户不存在', ErrorCode::USER_BAN);
            }

            Session::clear();

            //登入成功页面跳转
            session('ADMIN_ID', $result["id"]);
            session('name', $result["user_login"]);
            //更新最后登录ip及时间
            $result['last_login_ip']   = get_client_ip(0, true);
            $result['last_login_time'] = time();
            Db::name('admin_user')->update($result);

            ReqResp::outputSuccess([], '登录成功');
        } catch (\Exception $e) {
            ReqResp::outputFail($e);
        }
    }

    protected function getLoginTimes() {
        return Cache::get('admin_login_times');
    }
    protected function setLoginTimes() {
        if (Cache::has('admin_login')) {
            Cache::inc('admin_login_times');
        } else {
            Cache::set('admin_login', 1, 300);
            Cache::set('admin_login_times', 1, 300);
        }
    }

    public function logout () {
        Session::clear();
        $this->redirect(url('manage/login/login'));
    }
}
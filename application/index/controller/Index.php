<?php
namespace app\index\controller;

use app\index\model\User;

class Index extends \think\Controller{

  /*public function register() {
    if (request()->isPost()) {
        $postData = input('post.');
        if (!$this->checkCaptcha($postData['verifycode'])) {
            return $this->error('验证码错误！');
        }
        if (!$this->checkPassword($postData)) {
            return $this->error('密码为空或则两次密码不一致！');
        }
        $user = new User();
        $user->name = $postData['username'];
        $user->email = $postData['email'];
        $user->password = md5($postData['password']);
        $user->avatar = 'images/avatar.jpg';
        $user->created_at = intval(microtime(true));
        $user->save();
        return $this->success('注册成功！', 'index/login');
    }
    echo $this->fetch('register', ['user' => session('user')]);
}

    private function checkCaptcha($verifycode) {
        if(!captcha_check($verifycode)){
            return false;
        };
        return true;
    }

    private function checkPassword($postData) {
        if (!$postData['password']) {
            return false;
        }
        if ($postData['password'] != $postData['password_confirmation']) {
            return false;
        }
        return true;
    }

    public function login() {
        if (request()->isPost()) {
            $login = input('post.login');
            $password = input('post.password');
            $cond = array();
            $cond['name|email'] = $login;
            $cond['password'] = md5($password);
            $user = User::get($cond);
            if ($user) {
                session('user', $user);
                return $this->success('登陆成功！', 'topic/index');
            }
            return $this->error('用户名或者密码错误！');
        }
        echo $this->fetch('login', ['user' => session('user')]);
    }

    public function logout() {
        session('user', null);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $this->success('退出成功', 'topic/index');
    }*/
    public function register(){
        echo $this->fetch('register');
    }
}

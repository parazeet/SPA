<?php

namespace App\Controllers;

use App\ConnectDB;
use App\Model\User;

class AuthController
{
    private $user;

    public function __construct()
    {
        $database = new ConnectDB();
        $this->user = new User($database->getConnection());
    }

    public function index()
    {
        $this->checkAuth();

        require_once __DIR__ . "/../Views/Auth/login.php";
    }

    public function loginPost()
    {

        $user = $this->user->find(input('email'));

        if (!$user or !password_verify(input('password'), $user['password'])) {
            $_SESSION["errors"] = 'email или пароль указан не верно';

            return response()->redirect(url('login'));
        }

        $_SESSION["id"] = $user['id'];
        $_SESSION["user_name"] = $user['name'];
        $_SESSION['time'] = time();

        return response()->redirect(url('home'));
    }

    public function register()
    {
        $this->checkAuth();

        require_once __DIR__ . "/../Views/Auth/register.php";
    }

    public function registerStore()
    {
        if ($this->validation(input()->all())) {
            return response()->redirect(url('register'));
        }

        if ($this->user->create(input()->all())) {
            $auth = $this->user->find(input('email'));

            $_SESSION["id"] = $auth['id'];
            $_SESSION["user_name"] = $auth['name'];
            $_SESSION['time'] = time();
        }

        return response()->redirect(url('home'));
    }

    public function logout()
    {
        session_destroy();

        return response()->redirect(url('home'));
    }

    public function checkAuth()
    {
        if(isset($_SESSION["user_name"])) {
            return response()->redirect(url('home'));
        }
    }

    private function validation($request)
    {
        if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION["errors"][] = 'email указан не верно';
        }

        $countName = iconv_strlen($request['name']);
        $countPass = iconv_strlen($request['password']);

        if ($countName < 3 or $countPass >= 64) {
            $_SESSION["errors"][] = 'Имя должно быть от 3 до 64 знаков';
        }

        if ($countPass < 6 or $countPass >= 64) {
            $_SESSION["errors"][] = 'Пароль должен быть от 6 до 64 знаков';
        }

        if ($request['password'] !== $request['password_conf']) {
            $_SESSION["errors"][] = 'Пароли не совпадают';
        }

        if ($this->user->find($request['email'])) {
            $_SESSION["errors"][] = 'Данный email уже зарегестрирован';
        }

        return isset($_SESSION["errors"]);
    }
}
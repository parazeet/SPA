<?php

namespace App\Controllers;

use App\ConnectDB;
use App\Helpers\Error;
use App\Model\Cost;

class AdminController
{
    private $post;

    public function __construct()
    {
        if (empty($_SESSION['user_name'])) {
            redirect(url('login'));
        }

        $database = new ConnectDB();
        $this->post = new Cost($database->getConnection());
    }

    public function index()
    {
        $userId = $_SESSION['id'];
        $posts = $this->post->getMyPosts($userId) ?? [];
        require_once __DIR__ . "/../Views/Admin/myPosts.php";
    }

    public function create()
    {
        require_once __DIR__ . "/../Views/Admin/createPost.php";
    }

    public function store()
    {
        if ($this->validation(input()->all())) {
            return response()->redirect(url('createPost'));
        }

        if ($this->post->create(input()->all())) {
            $_SESSION["message"] = 'Пост успешно создан';

            return redirect(url('myPosts'));
        }

        $_SESSION["errors"] = 'Не удалось создать пост';

        return redirect(url('createPost'));
    }

    public function edit($id)
    {
        if (!$post = $this->post->first($id)) {
            Error::show();
        }

        require_once __DIR__ . "/../Views/Admin/editPost.php";
    }

    public function update($id)
    {
        if (!$this->post->first($id)) {
            Error::show();
        }

        if ($this->validation(input()->all())) {
            return response()->redirect(url('editPost'));
        }

        if ($this->post->update($id, input()->all())) {
            $_SESSION["message"] = 'Пост успешно сохранен';

            return redirect(url('myPosts'));
        }

        $_SESSION["errors"] = 'Не удалось сохранить пост';

        return redirect(url('editPost'));
    }

    public function delete($id)
    {
        if (!$this->post->first($id)) {
            Error::show();
        }

        if ($this->post->delete($id)) {
            $_SESSION["message"] = 'Пост успешно удалён';
            return redirect(url('myPosts'));
        }

        $_SESSION["errors"] = 'Не удалось сохранить пост';

        return redirect(url('myPosts'));
    }

    private function validation($request)
    {
        $countTitle = iconv_strlen($request['title']);
        $countBody = iconv_strlen($request['body']);

        if ($countTitle < 3 or $countTitle > 500) {
            $_SESSION["errors"][] = 'Title должен быть от 3 до 500 знаков';
        }

        if ($countBody < 20 or $countBody > 65535) {
            $_SESSION["errors"][] = 'Body должен быть больше 20 знаков. Напиши уже что-то достойное...!';
        }

        if (isset($_SESSION["errors"])) {
            $_SESSION["old"]['title'] = $request['title'];
            $_SESSION["old"]['body'] = $request['body'];
        }

        return isset($_SESSION["errors"]);
    }
}
<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Model\Cost;
use App\ConnectDB;

class HomeController
{
    private $costs;
    private $comings;

    public function __construct()
    {
        if (empty($_SESSION['user_name'])) {
            redirect(url('login'));
        }

        $database = new ConnectDB();
        $this->costs = new Cost($database->getConnection());
    }

    public function index()
    {
        $costs = $this->costs->getAll();
        $comings = $this->comings->getAll();

        require_once __DIR__ . "/../Views/index.php";
    }

    /*public function show($id = null)
    {
        $post = $this->post->first($id);

        if (!$post) {
            Error::show();
        }

        require_once __DIR__ . "/../Views/show.php";
    }

    public function postsList()
    {
        $posts = $this->post->getAll();

        require_once __DIR__ . "/../Views/list.php";
    }

    public function search()
    {
        $posts = $this->post->search(input('search'));

        require_once __DIR__ . "/../Views/index.php";
    }*/
}
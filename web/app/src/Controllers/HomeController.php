<?php

namespace App\Controllers;

use App\Helpers\Error;
use App\Helpers\PrepareDataCostsServices;
use App\Model\Cost;
use App\ConnectDB;

class HomeController
{
    public function __construct()
    {
        if (empty($_SESSION['user_name'])) {
            redirect(url('login'));
        }
    }

    public function index()
    {
        list($lastTen, $incomes, $costs, $difference) = (new PrepareDataCostsServices())->getData();

        require_once __DIR__ . "/../Views/index.php";
    }

    public function store()
    {
        $service = new PrepareDataCostsServices();

        if (!$service->create(input()->all())) {
            return response()->json([
                'success' => false,
            ]);
        }

        list($lastTen, $incomes, $costs, $difference) = $service->getData();

        return response()->json([
            'success' => true,
            'data' => [
                'lastTen' => $lastTen,
                'incomes' => $incomes,
                'costs' => $costs,
                'difference' => $difference
            ]
        ]);
    }
}
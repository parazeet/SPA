<?php

namespace App\Controllers;

use App\Helpers\PrepareDataCostsServices;

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
//dd($lastTen, $incomes, $costs, $difference);
        require_once __DIR__ . "/../Views/index.php";
    }

    public function store()
    {
        if ($errors = $this->validation(input()->all())) {
            return response()->json([
                'success' => false,
                'validation' => $errors
            ]);
        }

        $service = new PrepareDataCostsServices();

        if (!$service->create(input()->all())) {
            return response()->json([
                'success' => false
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

    private function validation($request)
    {
        $errors = [];

        if (!is_numeric($request['sum'])) {
            $errors[] = 'Поле "Сумма" не является integer or float';
        }

        if (!in_array($request['type'], array("costs", "income"))) {
            $errors[] = 'Поле "Тип" выбрано не верно';
        }

        return $errors;
    }
}
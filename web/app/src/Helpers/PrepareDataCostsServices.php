<?php


namespace App\Helpers;


use App\ConnectDB;
use App\Model\Cost;

class PrepareDataCostsServices
{
    private $costs;

    public function __construct()
    {
        $database = new ConnectDB();
        $this->costs = new Cost($database->getConnection());
    }

    public function getData()
    {
        $lastTen = $this->costs->getLastTen() ?? null;
        $incomes = $this->costs->getAllIncomes() ?? 0;
        $costs = $this->costs->getAllCosts() ?? 0;
        $difference = $incomes['sum'] - $costs['sum'];

        return [$lastTen, $incomes, $costs, $difference];
    }

    public function create($request)
    {
        return $this->costs->create($request);
    }
}
<?php


namespace App\ProfitAndLoss;


use App\Models\StockDataReading;

class StockDataReadingRepository
{
    public function create(array $rowData){
        /**
         *
         */
        StockDataReading::create($rowData);
    }
}

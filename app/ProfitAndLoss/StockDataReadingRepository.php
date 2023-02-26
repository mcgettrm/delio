<?php


namespace App\ProfitAndLoss;


use App\Models\StockDataReading;

/**
 * Class StockDataReadingRepository
 * @package App\ProfitAndLoss
 */
class StockDataReadingRepository
{

    /**
     * Takes an array of data and inserts it into the stock data model / db
     * @param array $rowData
     */
    public function create(array $rowData):void
    {
        StockDataReading::create($rowData);
    }


    /**
     * Gets the latest reading for a given stock symbol
     * @param string $symbol
     * @return mixed
     */
    public function getLatestReadingBySymbol(string $symbol): mixed
    {
        //Get the the TOP x number of stock readings ordered by created_at
        $stockData =  StockDataReading::where('symbol', $symbol)->orderBy('created_at','DESC')->first();

        return $stockData;
    }
}

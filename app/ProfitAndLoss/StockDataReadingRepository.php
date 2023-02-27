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
     * @param StockDataDTO $dto
     */
    public function create(StockDataDTO $dto):void
    {
        $rowData = [];
        $rowData['symbol'] = $dto->getSymbol();
        $rowData['current_value'] = $dto->getCurrentValue();
        $rowData['previous_day_close_value'] = $dto->getCloseValue();
        $rowData['effective_date'] = $dto->getEffectiveDate();
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

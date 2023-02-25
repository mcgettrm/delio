<?php


namespace App\ProfitAndLoss;


use DateTime;

/**
 * The responsibility of this class is to wrap around the Finnhub API as a mockable adapter
 * Class FinnhubAdapter
 * @package App\ProfitAndLoss
 */
class FinnhubAdapter
{
    /**
     * @param array $symbols
     * @param DateTime $date
     */
    public function getPricesForSymbolsAndDate(array $symbols, DateTime $date): array
    {
        //TODO::Return a DTO


    }
}

<?php


namespace App\ProfitAndLoss;


/**
 * Essentailly a helper class to get the "new" keyword out of business logic and facilitate testing
 * Class ProfitAndLossFactory
 * @package App\ProfitAndLoss
 */
class ProfitAndLossFactory
{
    /**
     * @return ProfitAndLossDTO
     */
    public function getNewProfitAndLossDTO(): ProfitAndLossDTO
    {
        return new ProfitAndLossDTO();
    }

    /***
     * @param string $symbol
     * @param float $currentValue
     * @param float $closeValue
     * @param string $effectiveDate
     * @return StockDataDTO
     */
    public function getNewStockDataDTO(string $symbol, float $currentValue, float $closeValue, string $effectiveDate): StockDataDTO
    {
        return new StockDataDTO($symbol, $currentValue, $closeValue, $effectiveDate);
    }
}

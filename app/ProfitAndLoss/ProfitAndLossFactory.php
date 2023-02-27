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
     * @return StockDataDTO
     */
    public function getNewStockDataDTO(): StockDataDTO
    {
        return new StockDataDTO();
    }
}

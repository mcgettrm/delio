<?php


namespace App\ProfitAndLoss;


use App\Models\StockDataReading;

/**
 * Interface ProfitAndLossStrategyInterface
 * @package App\ProfitAndLoss
 */
interface ProfitAndLossStrategyInterface
{
    /**
     * @param StockDataReading $stockDataReading
     * @param int $numberOfStock
     * @return float
     */
    public function calculateProfitAndLoss(StockDataReading $stockDataReading, int $numberOfStock = 1): float;
}

<?php


namespace App\ProfitAndLoss;


use App\Models\StockDataReading;

/**
 * Interface ProfitAndLossStrategyInterface
 * @package App\ProfitAndLoss
 */
interface ProfitAndLossStrategyInterface
{
    public function calculateProfitAndLoss(StockDataReading $stockDataReading): float;
}

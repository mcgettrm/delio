<?php


namespace App\ProfitAndLoss;

use App\Models\StockDataReading;
use JetBrains\PhpStorm\Pure;

/**
 * Simplest calculation of profit and loss
 * Class SimpleProfitAndLossStrategy
 * @package App\ProfitAndLoss
 */
class SimpleProfitAndLossStrategy implements ProfitAndLossStrategyInterface
{

    #[Pure] public function calculateProfitAndLoss(StockDataReading $stockDataReading): float
    {
        //TODO:: Don't trust floating point arithmetic in PHP
        return $stockDataReading->getCurrentValue() - $stockDataReading->getClosingValue();
    }
}

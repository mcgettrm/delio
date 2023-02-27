<?php


namespace App\ProfitAndLoss;

use App\Models\StockDataReading;
use JetBrains\PhpStorm\Pure;

/**
 * Simplest calculation of profit and loss
 * Class GrossProfitAndLossStrategy
 * @package App\ProfitAndLoss
 */
class GrossProfitAndLossStrategy implements ProfitAndLossStrategyInterface
{

    /**
     * @param StockDataReading $stockDataReading
     * @param int $numberOfStock
     * @return float
     */
    #[Pure] public function calculateProfitAndLoss(StockDataReading $stockDataReading, int $numberOfStock = 1): float
    {
        $current = $stockDataReading->getCurrentValue();
        $closing = $stockDataReading->getClosingValue();
        $modifiedByQuantity = ($current - $closing) * $numberOfStock;
        return round($modifiedByQuantity,2);
    }
}

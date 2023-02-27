<?php


namespace App\ProfitAndLoss;

/**
 * Class StockDataDTO
 * @package App\ProfitAndLoss
 */
class StockDataDTO
{

    /**
     * StockDataDTO constructor.
     * @param string $symbol
     * @param float $currentValue
     * @param float $closeValue
     * @param string $effectiveDate
     */
    public function __construct(
        private string $symbol,
        private float $currentValue,
        private float $closeValue,
        private string $effectiveDate
    )
    {
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return float
     */
    public function getCurrentValue(): float
    {
        return $this->currentValue;
    }

    /**
     * @return float
     */
    public function getCloseValue(): float
    {
        return $this->closeValue;
    }

    /**
     * @return string
     */
    public function getEffectiveDate(): string
    {
        return $this->effectiveDate;
    }
}

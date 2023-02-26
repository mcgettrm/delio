<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StockDataReading
 * @package App\Models
 */
class StockDataReading extends Model
{
    /**
     * @var array $guarded
     */
    protected array $guarded = [];

    /**
     * Getter method for returning yesterday's closing price
     * @return float
     */
    public function getClosingValue(): float
    {
        return $this->previous_day_close_value;
    }

    /**
     * Getter method for returning current stock value
     * @return float
     */
    public function getCurrentValue(): float
    {
        return $this->current_value;
    }

    /**
     * Getter method for returning the symbol of this Stock Reading
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

}

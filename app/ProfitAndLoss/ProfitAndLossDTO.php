<?php


namespace App\ProfitAndLoss;

use Illuminate\Contracts\Support\Jsonable;

/**
 * The purpose of this class is to codify the contract between the application domain layer and the various request vectors that may call it. (rather than passing arrays around)
 *
 * Class ProfitAndLossDTO
 * @package App\ProfitAndLoss
 */
class ProfitAndLossDTO implements Jsonable
{
    /**
     * The encapsulated value that holds profit and loss data. Immutable.
     * @var array
     */
    private array $profitAndLossData = [];

    /**
     * This method allows a profit/loss datum to be added to this class's data structure against a particular symbol
     * @param string $symbol
     * @param float $profitLoss
     */
    public function setSymbolProfitAndLoss(string $symbol, float $profitLoss): void
    {
        $this->profitAndLossData[$symbol] = $profitLoss;
    }

    /**
     * This method ensures any class instances can be appropriately used where a string may otherwise be (e.g. HTTP responses)
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->profitAndLossData, JSON_PRETTY_PRINT);
    }

    /**
     * Allows the DTO to be passed directly to an illuminate JSON respnse and behave correctly
     * @param int $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->profitAndLossData);
    }
}

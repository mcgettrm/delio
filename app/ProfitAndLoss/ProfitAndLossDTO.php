<?php


namespace App\ProfitAndLoss;

/**
 * The purpose of this class is to codify the contract between the application domain layer and the various request vectors that may call it. (rather than passing arrays around)
 *
 * Class ProfitAndLossDTO
 * @package App\ProfitAndLoss
 */
class ProfitAndLossDTO
{
    /**
     * The encapsulated value that holds profit and loss data. Immutable.
     * @var array
     */
    private array $profitAndLossData = [];

    public function setSymbolProfitAndLoss(string $symbol, float $profitLoss):void {
        $this->profitAndLossData[$symbol] = $profitLoss;
    }

    public function __toString():string {
        return json_encode($this->profitAndLossData, JSON_PRETTY_PRINT);
    }
}

<?php
namespace App\ProfitAndLoss;

/**
 * This class is responsible for generating a list of profit and losses between today and yesterday for a given set of ticker symbols
 * Class ProfitAndLossFacade
 * @package App\ProfitAndLoss
 */
class ProfitAndLossFacade
{
    public function retrievePersistAndReturnProfitAndLoss():ProfitAndLossDTO {
        $this->retrieve();
        $this->persist();
        return $this->return();
    }

    private function retrieve(){}

    private function persist(){}

    private function return():ProfitAndLossDTO {
        //TODO::Load via Factory method - get "new" keyword out of business layer.
        $dto = new ProfitAndLossDTO();
        $dto->setSymbolProfitAndLoss('AAPL', -2.13);
        $dto->setSymbolProfitAndLoss('MSFT', 4.19);

        return $dto;
    }
}

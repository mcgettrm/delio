<?php
namespace App\ProfitAndLoss;

/**
 * This class is responsible for generating a list of profit and losses between today and yesterday for a given set of ticker symbols
 * Class ProfitAndLossFacade
 * @package App\ProfitAndLoss
 */
class ProfitAndLossFacade
{
    /**
     * @var FinnhubAdapter $finnhubAdapter
     */
    private FinnhubAdapter $finnhubAdapter;

    /**
     * ProfitAndLossFacade constructor.
     * @param FinnhubAdapter $finnhubAdapter
     */
    public function __construct(FinnhubAdapter $finnhubAdapter)
    {
        $this->finnhubAdapter = $finnhubAdapter;
    }

    /**
     * Public accessor the Profit and Loss subsystem, this interface abstracts the underlying complexity of the operations required to generate the profit and loss output.
     * @param array|string[] $symbols
     * @return ProfitAndLossDTO
     */
    public function retrievePersistAndReturnProfitAndLoss(array $symbols = ['MSFT','AAPL']): ProfitAndLossDTO
    {
        $this->retrieve($symbols);
        $this->persist();
        $this->calculate();
        return $this->return();
    }

    private function calculate(){

    }

    /**
     * @param array $symbols
     */
    private function retrieve(array $symbols)
    {
       $prices = $this->finnhubAdapter->getPricesForSymbols($symbols);
    }

    private function persist()
    {

    }

    private function return(): ProfitAndLossDTO
    {
        //TODO::Load via Factory method - get "new" keyword out of business layer.
        $dto = new ProfitAndLossDTO();
        $dto->setSymbolProfitAndLoss('AAPL', -2.13);
        $dto->setSymbolProfitAndLoss('MSFT', 4.19);

        return $dto;
    }
}

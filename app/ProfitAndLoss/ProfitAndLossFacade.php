<?php
namespace App\ProfitAndLoss;

use App\Models\StockDataReading;

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
     * @var StockDataReadingRepository $stockDataReadingRepository
     */
    private StockDataReadingRepository $stockDataReadingRepository;

    /**
     * ProfitAndLossFacade constructor.
     * @param FinnhubAdapter $finnhubAdapter
     * @param StockDataReadingRepository $stockDataReadingRepository
     */
    public function __construct(
        FinnhubAdapter $finnhubAdapter,
        StockDataReadingRepository $stockDataReadingRepository
    )
    {
        $this->finnhubAdapter = $finnhubAdapter;
        $this->stockDataReadingRepository = $stockDataReadingRepository;
    }

    /**
     * Public accessor the Profit and Loss subsystem, this interface abstracts the underlying complexity of the operations required to generate the profit and loss output.
     * @param array|string[] $symbols
     * @return ProfitAndLossDTO
     * @throws \Exception
     */
    public function retrievePersistAndReturnProfitAndLoss(array $symbols = ['MSFT','AAPL']): ProfitAndLossDTO
    {
        $this->sanitiseSymbols($symbols);
        $data = $this->retrieve($symbols);
        $this->persist($data);
        $calculatedProfitAndLoss = $this->calculate();
        return $this->return($calculatedProfitAndLoss);
    }

    /**
     *
     */
    private function calculate(): array
    {
        return [];
    }

    /**
     * @param array $symbols
     * @throws \Exception
     */
    private function sanitiseSymbols(array &$symbols){
        //TODO:: Probably merits a class of its own
        foreach($symbols as &$symbol){
            //Check whether this symbol complies with database length
            if(!is_string($symbol) || (mb_strlen($symbol) > 16 )){
                throw new \Exception('Invalid symbol passed to ProfitAndLossFacade');
            }
            $symbol = strtoupper($symbol);
        }
    }

    /**
     * @param array $symbols
     * @throws \Exception
     */
    private function retrieve(array $symbols): array
    {
       return $this->finnhubAdapter->getStockDataForSymbols($symbols);
    }

    /**
     * @param array $data
     */
    private function persist(array $data): void
    {
        foreach($data as $datum){
            $this->stockDataReadingRepository->create($datum);
        }
    }

    /**
     * @return StockDataReading
     */
    private function getNewModel(){
        //TODO:: Move to factory
        return new StockDataReading();
    }

    private function return(array $calculatedProfitAndLoss): ProfitAndLossDTO
    {
        //TODO::Load via Factory method - get "new" keyword out of business layer.
        $dto = new ProfitAndLossDTO();
        $dto->setSymbolProfitAndLoss('AAPL', -2.13);
        $dto->setSymbolProfitAndLoss('MSFT', 4.19);

        return $dto;
    }
}

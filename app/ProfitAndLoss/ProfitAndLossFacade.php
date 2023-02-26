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
     * @var ProfitAndLossStrategyInterface
     */
    private ProfitAndLossStrategyInterface $profitAndLossStrategy;
    /**
     * ProfitAndLossFacade constructor.
     * @param FinnhubAdapter $finnhubAdapter
     * @param StockDataReadingRepository $stockDataReadingRepository
     * @param ProfitAndLossStrategyInterface $profitAndLossStrategy
     */
    public function __construct(
        FinnhubAdapter $finnhubAdapter,
        StockDataReadingRepository $stockDataReadingRepository,
        ProfitAndLossStrategyInterface $profitAndLossStrategy
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
        $data = $this->finnhubAdapter->getStockDataForSymbols($symbols);

        //Note, specific requirement that these be persisted first and then retrieved before being used in calculation
        $this->persist($data);
        $latestReadings = $this->getLatestReadingsForSymbols($symbols);
        return $this->calculate($latestReadings);
    }

    /**
     * @param array $symbols
     * @return array
     */
    private function getLatestReadingsForSymbols(array $symbols): array
    {
        $readings = [];
        foreach($symbols as $symbol){
            $readings[] = $this->stockDataReadingRepository->getLatestReadingBySymbol($symbol);
        }
        return $readings;
    }

    /**
     * Takes an array of StockDataReading models and calculates the profit and loss for each one
     * @param array $readings
     * @param int $numberOfShares
     * @return ProfitAndLossDTO
     */
    private function calculate(array $readings, int $numberOfShares = 10): ProfitAndLossDTO
    {
        //TODO:: NOT HERE - Factory required
        $dto = new ProfitAndLossDTO();

        /**
         * @var StockDataReading $reading
         */
        foreach($readings as $reading){
            $pl = $this->profitAndLossStrategy->calculateProfitAndLoss($reading);
            $symbol = $reading->getSymbol();
            $dto->setSymbolProfitAndLoss($symbol, $pl);
        }

        return $dto;
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
     * @param array $data
     */
    private function persist(array $data): void
    {
        foreach($data as $datum){
            $this->stockDataReadingRepository->create($datum);
        }
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

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
     * ProfitAndLossFacade constructor.
     * @param FinnhubAdapter $finnhubAdapter
     * @param StockDataReadingRepository $stockDataReadingRepository
     * @param ProfitAndLossStrategyInterface $profitAndLossStrategy
     * @param ProfitAndLossFactory $profitAndLossFactory
     */
    public function __construct(
        private FinnhubAdapter $finnhubAdapter,
        private StockDataReadingRepository $stockDataReadingRepository,
        private ProfitAndLossStrategyInterface $profitAndLossStrategy,
        private ProfitAndLossFactory $profitAndLossFactory
    )
    {

    }

    /**
     * Public accessor the Profit and Loss subsystem, this interface abstracts the underlying complexity of the operations required to generate the profit and loss output.
     * @param array|string[] $symbols
     * @param int $numberOfShares
     * @return ProfitAndLossDTO
     * @throws \Exception
     */
    public function retrievePersistAndReturnProfitAndLoss(array $symbols = ['MSFT','AAPL'], int $numberOfShares = 1): ProfitAndLossDTO
    {
        $this->sanitiseSymbols($symbols);
        $data = $this->finnhubAdapter->getStockDataForSymbols($symbols);

        //Note, specific requirement that these be persisted first and then retrieved before being used in calculation
        $this->persist($data);
        $latestReadings = $this->getLatestReadingsForSymbols($symbols);
        return $this->calculate($latestReadings, $numberOfShares);
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
    private function calculate(array $readings, int $numberOfShares = 1): ProfitAndLossDTO
    {
        $dto = $this->profitAndLossFactory->getNewProfitAndLossDTO();

        /**
         * @var StockDataReading $reading
         */
        foreach($readings as $reading){

            $pl = $this->profitAndLossStrategy->calculateProfitAndLoss($reading, $numberOfShares);
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
            if(!is_string($symbol) || !strlen($symbol) || (mb_strlen($symbol) > 16 )){
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
}

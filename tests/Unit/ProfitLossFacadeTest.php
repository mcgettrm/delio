<?php


namespace Tests\Unit;

use App\Models\StockDataReading;
use App\ProfitAndLoss\FinnhubAdapter;
use App\ProfitAndLoss\GrossProfitAndLossStrategy;
use App\ProfitAndLoss\ProfitAndLossFacade;
use App\ProfitAndLoss\ProfitAndLossFactory;
use App\ProfitAndLoss\StockDataDTO;
use App\ProfitAndLoss\StockDataReadingRepository;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;

class ProfitLossFacadeTest extends TestCase
{
    /**
     * Sets up the environment for the facade test
     */
    public function setUp(): void
    {
        parent::setUp();

    }

    /**
     * Get a copy of the gross profit and loss strategy. Not mocked because the class is pure.
     * @return GrossProfitAndLossStrategy
     */
    #[Pure] protected function getGrossProfitAndLossStrategy()
    {
        return new GrossProfitAndLossStrategy();
    }

    /**
     * Gets a "No bells and whistles" facade with fully mocked dependencies
     * @return ProfitAndLossFacade
     */
    protected function getBaseFacadeWithMockedDependencies(): ProfitAndLossFacade
    {
        $finnhubAdapterDummy = $this->createMock(FinnhubAdapter::class);
        $stockDataReadingRepoDummy = $this->createMock(StockDataReadingRepository::class);
        $grossPLStrategy = $this->getGrossProfitAndLossStrategy();
        $factoryDummy = $this->createMock(ProfitAndLossFactory::class);

        return new ProfitAndLossFacade(
            $finnhubAdapterDummy,
            $stockDataReadingRepoDummy,
            $grossPLStrategy,
            $factoryDummy
        );
    }


    /**
     * Tests that an exception is thrown if a stock symbol is too long
     * @throws \Exception
     */
    public function testExceptionThrownWhenInvalidSymbolPassed()
    {
        $profitAndLossFacade = $this->getBaseFacadeWithMockedDependencies();
        $this->expectException(\Exception::class);

        $profitAndLossFacade->retrievePersistAndReturnProfitAndLoss(['THISSYMBOLISJUSTWAYTOOLONGTOBEVALIDREALLYREALLYLONG']);
    }

    /**
     * Tests that an exception is thrown if floats are passed as stock symbols
     * @throws \Exception
     */
    public function testExceptionThrownWhenFloatSymbolIsPassed()
    {
        $profitAndLossFacade = $this->getBaseFacadeWithMockedDependencies();
        $this->expectException(\Exception::class);

        $profitAndLossFacade->retrievePersistAndReturnProfitAndLoss([74.56]);
    }

    /**
     * Tests that an exception is thrown if the stock symbol is too short
     * @throws \Exception
     */
    public function testExceptionThrownWhenStockSymbolIsTooShort()
    {
        $profitAndLossFacade = $this->getBaseFacadeWithMockedDependencies();
        $this->expectException(\Exception::class);

        $profitAndLossFacade->retrievePersistAndReturnProfitAndLoss(['']);
    }


    /**
     *
     * @param string $symbol
     * @param float $current
     * @param float $closing
     * @return StockDataDTO
     */
    protected function getStockArrayItem(string $symbol, float $current, float $closing): StockDataDTO
    {
        return new StockDataDTO($symbol,$current, $closing, date('Y-m-d H:i:s'));
    }


    /**
     * Simply tests that, given appropriate input, the dependencies are called as expected
     * @throws \Exception
     */
    public function testDependenciesAreCalledAppropriateNumberOfTimes()
    {
        $symbols = ['MSFT', 'AAPL'];

        $numberOfSymbols = count($symbols);

        $adapterResponse = [];
        $adapterResponse['MSFT'] = $this->getStockArrayItem('MSFT', 22.24,19.23);
        $adapterResponse['AAPL'] = $this->getStockArrayItem('AAPL', 21.23,15.29);

        $finnhubAdapterMock = $this->createMock(FinnhubAdapter::class);
        $finnhubAdapterMock->expects($this->once())->method('getStockDataForSymbols')->willReturn($adapterResponse);

        $stockDataReading = $this->createMock(StockDataReading::class);

        $stockDataReadingRepoMock = $this->createMock(StockDataReadingRepository::class);
        $stockDataReadingRepoMock->expects($this->exactly($numberOfSymbols))->method('create');
        $stockDataReadingRepoMock->expects($this->exactly($numberOfSymbols))->method('getLatestReadingBySymbol')->willReturn($stockDataReading);

        $grossPLStrategyMock = $this->createMock(GrossProfitAndLossStrategy::class);
        $grossPLStrategyMock->expects($this->exactly($numberOfSymbols))->method('calculateProfitAndLoss');

        $factoryDummy = $this->createMock(ProfitAndLossFactory::class);

        $facade = new ProfitAndLossFacade(
            $finnhubAdapterMock,
            $stockDataReadingRepoMock,
            $grossPLStrategyMock,
            $factoryDummy
        );

        $dto = $facade->retrievePersistAndReturnProfitAndLoss($symbols);
    }

}

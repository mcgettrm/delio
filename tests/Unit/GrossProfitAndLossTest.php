<?php

namespace Tests\Unit;

use App\Models\StockDataReading;
use App\ProfitAndLoss\GrossProfitAndLossStrategy;
use PHPUnit\Framework\TestCase;

class GrossProfitAndLossTest extends TestCase
{
    protected GrossProfitAndLossStrategy $grossProfitLossStrategy;

    /**
     * General test data, current_value|closing_value|quantity|expected_result
     * @var array|\float[][]
     */
    protected array $testInputs =  [
        [244.93,255.47,1,-10.54]
    ];

    /**
     * Sets up the class for tests
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->grossProfitLossStrategy = new GrossProfitAndLossStrategy();
    }

    /**
     * Tests that negative floats can be returned from the strategy.
     * @return void
     */
    public function test_resultCanBeNegative()
    {
        $mockReading = $this->getMockBuilder(StockDataReading::class)->getMock();
        $mockReading->method('getCurrentValue')->willReturn(244.93);
        $mockReading->method('getClosingValue')->willReturn(255.47);

        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading, 1);
        $this->assertLessThan(0.00, $actual, 'A negative float was not returned when appropriate.');
    }

    /**
     * Checks that we get the expected result down the "happy path".
     *
     * This test actually also covers the scenario that results are returned to two decimal places.
     * During testing, a current value of 41.20 and a closing value of 39.13 with a quantity of 1 would return
     *  2.0700000000000003 <-- we want such a number to be 2dp.
     */
    public function test_resultReturnsCorrectCalculationForSingleQuantity(){
        $mockReading = $this->getMockBuilder(StockDataReading::class)->getMock();
        $mockReading->method('getCurrentValue')->willReturn(41.20);
        $mockReading->method('getClosingValue')->willReturn(39.13);

        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading, 1);
        $expected = 2.07;
        $this->assertEquals($expected,$actual, "Incorrect result returned for calculation. Expected: {$expected} actual: {$actual}");
    }

}

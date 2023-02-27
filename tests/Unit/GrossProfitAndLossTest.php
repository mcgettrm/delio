<?php

namespace Tests\Unit;

use App\Models\StockDataReading;
use App\ProfitAndLoss\GrossProfitAndLossStrategy;
use PHPUnit\Framework\TestCase;

class GrossProfitAndLossTest extends TestCase
{

    /**
     * @var GrossProfitAndLossStrategy $grossProfitLossStrategy
     */
    protected GrossProfitAndLossStrategy $grossProfitLossStrategy;


    /**
     * Sets up the class for tests
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->grossProfitLossStrategy = new GrossProfitAndLossStrategy();
    }

    /**
     * Apply DRY: Gets a mock reading that will return the requested parameters
     * @param float $current
     * @param float $closing
     * @return StockDataReading|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getReadingMock(float $current, float $closing): \PHPUnit\Framework\MockObject\MockObject|StockDataReading
    {
        $mockReading = $this->createMock(StockDataReading::class);
        $mockReading->method('getCurrentValue')->willReturn($current);
        $mockReading->method('getClosingValue')->willReturn($closing);
        return $mockReading;
    }

    /**
     * Tests that negative floats can be returned from the strategy.
     * @return void
     */
    public function test_resultCanBeNegative()
    {
        $mockReading = $this->getReadingMock(12.13,13.90);

        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading);
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
        $mockReading = $this->getReadingMock(41.20,39.13);

        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading);
        $expected = 2.07;
        $this->assertEquals($expected,$actual, "Incorrect result returned for calculation. Expected: {$expected} actual: {$actual}");
    }

    /**
     * Tests that the gross profit and loss returned are appropriately modified by the quantity of shares purchased
     */
    public function test_resultAppropriatelyModifiedByQuantity(){
        $mockReading = $this->getReadingMock(13.20, 13.13);
        $quantity = 100;

        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading, $quantity);
        $expected = 7;
        $this->assertEquals($expected,$actual, "Quantity was not handled correctly. Expected: {$expected} actual: {$actual}");
    }

    /**
     * Check that edge cases are handled correctly
     */
    public function test_GrossStrategyHandlesZeroQuantityGracefully(){
        $mockReading = $this->getReadingMock(139.24, 34.90);
        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading, 0);
        $this->assertEquals(0,$actual, "Gross strategy did not handle zero quantity correctly.");
    }

    /**
     * This test checks that the inputted values can be zero without causing any breakages
     */
    public function test_GrossStrategyHandlesZeroValueInputsGracefully(){
        $mockReading = $this->getReadingMock(139.24, 0.0);
        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading);
        $this->assertEquals(139.24,$actual, "Gross strategy did not handle zero closing value correctly.");

        $mockReading = $this->getReadingMock(0.00, 34.90);
        $actual = $this->grossProfitLossStrategy->calculateProfitAndLoss($mockReading);

        $this->assertEquals(-34.90,$actual, "Gross strategy did not handle zero current value correctly.");
    }
}

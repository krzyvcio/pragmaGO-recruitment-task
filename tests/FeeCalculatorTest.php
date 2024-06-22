<?php

namespace PragmaGoTech\Interview\Tests;

use PHPUnit\Framework\TestCase;
use PragmaGoTech\Interview\Calculator\StandardFeeCalculator;
use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\FeeStructureService;
use PragmaGoTech\Interview\Service\FeeRoundingService;

class FeeCalculatorTest extends TestCase
{
    private StandardFeeCalculator $calculator;

    protected function setUp(): void
    {
        $feeStructureService = new FeeStructureService();
        $feeRoundingService = new FeeRoundingService();
        $this->calculator = new StandardFeeCalculator($feeStructureService, $feeRoundingService);
    }

    /**
     * @dataProvider provideLoanProposals
     */
    public function testCalculate(int $term, float $amount, float $expectedFee): void
    {
        $proposal = new LoanProposal($term, $amount);
        $fee = $this->calculator->calculate($proposal);
        $this->assertEquals($expectedFee, $fee);
    }

    public function provideLoanProposals(): array
    {
        return [
            'Example from README 1' => [24, 11500, 460],
            'Example from README 2' => [12, 19250, 385],
            'Minimum amount 12 months' => [12, 1000, 50],
            'Minimum amount 24 months' => [24, 1000, 70],
            'Maximum amount 12 months' => [12, 20000, 400],
            'Maximum amount 24 months' => [24, 20000, 800],
            'Interpolation 12 months' => [12, 2500, 90],
            'Interpolation 24 months' => [24, 4500, 180],
            'Rounding 12 months' => [12, 5100, 105],
            'Rounding 24 months' => [24, 11600, 465],
        ];
    }

    public function testInvalidTerm(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $proposal = new LoanProposal(36, 10000);
        $this->calculator->calculate($proposal);
    }

    public function testAmountTooLow(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $proposal = new LoanProposal(12, 500);
        $this->calculator->calculate($proposal);
    }

    public function testAmountTooHigh(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $proposal = new LoanProposal(24, 25000);
        $this->calculator->calculate($proposal);
    }
}

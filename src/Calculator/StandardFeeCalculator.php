<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Calculator;

use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\FeeStructureService;
use PragmaGoTech\Interview\Service\FeeRoundingService;

class StandardFeeCalculator implements FeeCalculatorInterface
{
    private FeeStructureService $feeStructureService;
    private FeeRoundingService $feeRoundingService;

    public function __construct(
        FeeStructureService $feeStructureService,
        FeeRoundingService $feeRoundingService
    ) {
        $this->feeStructureService = $feeStructureService;
        $this->feeRoundingService = $feeRoundingService;
    }

    public function calculate(LoanProposal $proposal): float
    {
        $amount = $proposal->amount();
        $term = $proposal->term();

        $this->validateProposal($amount, $term);

        $feeStructure = $this->feeStructureService->getFeeStructure($term);
        $fee = $this->interpolateFee($amount, $feeStructure);

        return $this->feeRoundingService->roundFee($fee, $amount);
    }

    private function validateProposal(float $amount, int $term): void
    {
        if ($amount < 1000 || $amount > 20000) {
            throw new \InvalidArgumentException("Loan amount must be between 1,000 PLN and 20,000 PLN.");
        }

        if (!in_array($term, [12, 24])) {
            throw new \InvalidArgumentException("Loan term must be either 12 or 24 months.");
        }
    }

    private function interpolateFee(float $amount, array $feeStructure): float
    {
        $lowerBound = 0;
        $upperBound = 0;
        $lowerFee = 0;
        $upperFee = 0;

        foreach ($feeStructure as $breakpoint => $fee) {
            if ($amount <= $breakpoint) {
                $upperBound = $breakpoint;
                $upperFee = $fee;
                break;
            }
            $lowerBound = $breakpoint;
            $lowerFee = $fee;
        }

        if ($lowerBound === 0) {
            return $upperFee;
        }

        if ($upperBound === 0) {
            return $lowerFee;
        }

        $ratio = ($amount - $lowerBound) / ($upperBound - $lowerBound);
        return $lowerFee + ($upperFee - $lowerFee) * $ratio;
    }

}

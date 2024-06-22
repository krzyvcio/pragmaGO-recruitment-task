<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Calculator;

use PragmaGoTech\Interview\Model\LoanProposal;
use PragmaGoTech\Interview\Service\FeeStructureService;
use PragmaGoTech\Interview\Service\FeeRoundingService;

// StandardFeeCalculator class implements the FeeCalculatorInterface
class StandardFeeCalculator implements FeeCalculatorInterface
{
    // Declare the FeeStructureService and FeeRoundingService properties
    private FeeStructureService $feeStructureService;
    private FeeRoundingService $feeRoundingService;

    // Constructor method to initialize the FeeStructureService and FeeRoundingService properties
    public function __construct(
        FeeStructureService $feeStructureService,
        FeeRoundingService $feeRoundingService
    ) {
        $this->feeStructureService = $feeStructureService;
        $this->feeRoundingService = $feeRoundingService;
    }

    // Method to calculate the fee based on the loan proposal
    public function calculate(LoanProposal $proposal): float
    {
        $amount = $proposal->amount();
        $term = $proposal->term();

        // Validate the loan proposal
        $this->validateProposal($amount, $term);

        // Get the fee structure based on the term
        $feeStructure = $this->feeStructureService->getFeeStructure($term);
        // Interpolate the fee based on the amount and fee structure
        $fee = $this->interpolateFee($amount, $feeStructure);

        // Return the rounded fee
        return $this->feeRoundingService->roundFee($fee, $amount);
    }

    // Method to validate the loan proposal
    private function validateProposal(float $amount, int $term): void
    {
        // Check if the loan amount is within the valid range
        if ($amount < 1000 || $amount > 20000) {
            throw new \InvalidArgumentException("Loan amount must be between 1,000 PLN and 20,000 PLN.");
        }

        // Check if the loan term is valid
        if (!in_array($term, [12, 24])) {
            throw new \InvalidArgumentException("Loan term must be either 12 or 24 months.");
        }
    }

    // Method to interpolate the fee based on the amount and fee structure
    private function interpolateFee(float $amount, array $feeStructure): float
    {
        $lowerBound = 0;
        $upperBound = 0;
        $lowerFee = 0;
        $upperFee = 0;

        // Loop through the fee structure to find the fee based on the amount
        foreach ($feeStructure as $breakpoint => $fee) {
            if ($amount <= $breakpoint) {
                $upperBound = $breakpoint;
                $upperFee = $fee;
                break;
            }
            $lowerBound = $breakpoint;
            $lowerFee = $fee;
        }

        // If the lower bound is 0, return the upper fee
        if ($lowerBound === 0) {
            return $upperFee;
        }

        // If the upper bound is 0, return the lower fee
        if ($upperBound === 0) {
            return $lowerFee;
        }

        // Calculate the ratio and interpolate the fee
        $ratio = ($amount - $lowerBound) / ($upperBound - $lowerBound);
        return $lowerFee + ($upperFee - $lowerFee) * $ratio;
    }

}
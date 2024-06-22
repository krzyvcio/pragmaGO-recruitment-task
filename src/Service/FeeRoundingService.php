<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

// FeeRoundingService class is responsible for rounding the fee
class FeeRoundingService
{
    // The roundFee method takes the fee and loan amount as parameters
    // It calculates the total of the fee and loan amount
    // Then it rounds the total to the nearest multiple of 5
    // Finally, it returns the rounded fee by subtracting the loan amount from the rounded total
    public function roundFee(float $fee, float $loanAmount): float
    {
        $total = $fee + $loanAmount;
        $roundedTotal = ceil($total / 5) * 5;
        return $roundedTotal - $loanAmount;
    }
}
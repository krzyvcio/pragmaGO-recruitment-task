<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

class FeeRoundingService
{
    public function roundFee(float $fee, float $loanAmount): float
    {
        $total = $fee + $loanAmount;
        $roundedTotal = ceil($total / 5) * 5;
        return $roundedTotal - $loanAmount;
    }
}

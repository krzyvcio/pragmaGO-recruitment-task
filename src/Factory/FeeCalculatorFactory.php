<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Factory;

use PragmaGoTech\Interview\Calculator\FeeCalculatorInterface;
use PragmaGoTech\Interview\Calculator\StandardFeeCalculator;
use PragmaGoTech\Interview\Service\FeeStructureService;
use PragmaGoTech\Interview\Service\FeeRoundingService;

class FeeCalculatorFactory
{
    public static function create(): FeeCalculatorInterface
    {
        $feeStructureService = new FeeStructureService();
        $feeRoundingService = new FeeRoundingService();

        return new StandardFeeCalculator($feeStructureService, $feeRoundingService);
    }
}

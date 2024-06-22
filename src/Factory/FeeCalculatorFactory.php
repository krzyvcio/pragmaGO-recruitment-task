<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Factory;

use PragmaGoTech\Interview\Calculator\FeeCalculatorInterface;
use PragmaGoTech\Interview\Calculator\StandardFeeCalculator;
use PragmaGoTech\Interview\Service\FeeStructureService;
use PragmaGoTech\Interview\Service\FeeRoundingService;

// FeeCalculatorFactory class is responsible for creating instances of FeeCalculatorInterface
class FeeCalculatorFactory
{
    // The create method is a factory method that creates and returns an instance of StandardFeeCalculator
    public static function create(): FeeCalculatorInterface
    {
        // Create an instance of FeeStructureService
        $feeStructureService = new FeeStructureService();
        // Create an instance of FeeRoundingService
        $feeRoundingService = new FeeRoundingService();

        // Create and return an instance of StandardFeeCalculator with the created services
        return new StandardFeeCalculator($feeStructureService, $feeRoundingService);
    }
}
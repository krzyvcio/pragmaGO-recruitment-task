<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Service;

// FeeStructureService class is responsible for managing the fee structure
class FeeStructureService
{

    // Define the fee structure for 12 and 24 month terms
    private const FEE_STRUCTURE = [
        12 => [
            1000 => 50, 2000 => 90, 3000 => 90, 4000 => 115, 5000 => 100,
            6000 => 120, 7000 => 140, 8000 => 160, 9000 => 180, 10000 => 200,
            11000 => 220, 12000 => 240, 13000 => 260, 14000 => 280, 15000 => 300,
            16000 => 320, 17000 => 340, 18000 => 360, 19000 => 380, 20000 => 400
        ],
        24 => [
            1000 => 70, 2000 => 100, 3000 => 120, 4000 => 160, 5000 => 200,
            6000 => 240, 7000 => 280, 8000 => 320, 9000 => 360, 10000 => 400,
            11000 => 440, 12000 => 480, 13000 => 520, 14000 => 560, 15000 => 600,
            16000 => 640, 17000 => 680, 18000 => 720, 19000 => 760, 20000 => 800
        ]
    ];


    // The getFeeStructure method takes the term as a parameter
    public function getFeeStructure(int $term): array
    {
        if (!isset(self::FEE_STRUCTURE[$term])) {
            throw new \InvalidArgumentException("Invalid term: $term");
        }
        return self::FEE_STRUCTURE[$term];
    }

    // The getAvailableTerms method returns the available terms
    public function getAvailableTerms(): array
    {
        return array_keys(self::FEE_STRUCTURE);
    }

    // The getMinAmount method takes the term as a parameter
    public function getMinAmount(int $period): float
    {
        return min(array_keys(self::FEE_STRUCTURE[$period]));
    }

    // The getMaxAmount method takes the term as a parameter
    public function getMaxAmount(int $period): float
    {
        return max(array_keys(self::FEE_STRUCTURE[$period]));
    }
}

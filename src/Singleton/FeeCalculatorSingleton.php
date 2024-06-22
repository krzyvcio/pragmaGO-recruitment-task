<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Singleton;

use PragmaGoTech\Interview\Calculator\FeeCalculatorInterface;
use PragmaGoTech\Interview\Factory\FeeCalculatorFactory;

class FeeCalculatorSingleton
{
    private static ?FeeCalculatorInterface $instance = null;

    private function __construct()
    {
        // Prywatny konstruktor, aby zapobiec tworzeniu instancji z zewnątrz
    }

    public static function getInstance(): FeeCalculatorInterface
    {
        if (self::$instance === null) {
            self::$instance = FeeCalculatorFactory::create();
        }

        return self::$instance;
    }

    // Zapobieganie klonowaniu obiektu
    private function __clone()
    {
    }

    // Zapobieganie deserializacji obiektu
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
}

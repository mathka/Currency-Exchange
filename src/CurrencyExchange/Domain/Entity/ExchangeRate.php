<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Entity;

use App\CurrencyExchange\Domain\Enum\Currency;
use Laminas\Stdlib\Exception\DomainException;

class ExchangeRate
{
    public function __construct(
        private Currency $originalCurrency,
        private Currency $targetCurrency,
        private float $rate,
    ) {
        if ($this->originalCurrency === $this->targetCurrency) {
            throw new DomainException('The exchange rate must contain two different currencies');
        }
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getTargetCurrency(): Currency
    {
        return $this->targetCurrency;
    }
}

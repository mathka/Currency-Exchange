<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Service;

use App\CurrencyExchange\Domain\Entity\ExchangeRate;
use App\CurrencyExchange\Domain\ValueObject\Money;

class CurrencyExchangeCalculator
{
    public function calculate(Money $money, ExchangeRate $exchangeRate): Money
    {
        return new Money(
            $exchangeRate->getTargetCurrency(),
            (int) round($money->getAmount() * $exchangeRate->getRate())
        );
    }
}

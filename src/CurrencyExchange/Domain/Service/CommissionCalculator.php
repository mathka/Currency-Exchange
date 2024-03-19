<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Service;

use App\CurrencyExchange\Domain\ValueObject\Money;

class CommissionCalculator
{
    private const COMMISSION_FRACTION = 0.01;

    public function calculate(Money $exchangedMoney): Money
    {
        return new Money(
            $exchangedMoney->getCurrency(),
            (int) round($exchangedMoney->getAmount() * self::COMMISSION_FRACTION)
        );
    }
}

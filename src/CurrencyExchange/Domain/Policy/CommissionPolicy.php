<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Policy;

use App\CurrencyExchange\Domain\ValueObject\Money;

interface CommissionPolicy
{
    public function apply(Money $exchangedMoney): Money;
}

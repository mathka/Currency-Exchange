<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Repository;

use App\CurrencyExchange\Domain\Entity\ExchangeRate;
use App\CurrencyExchange\Domain\Enum\Currency;

interface ExchangeRateRepository
{
    public function findBy(Currency $originalCurrency, Currency $targetCurrency): ?ExchangeRate;
}

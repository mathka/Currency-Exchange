<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Infrastructure\Repository;

use App\CurrencyExchange\Domain\Entity\ExchangeRate;
use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\Repository\ExchangeRateRepository as ExchangeRateRepositoryInterface;

/**
 * An implementation created to write an integration test
 */
class ExchangeRateRepository implements ExchangeRateRepositoryInterface
{
    private const EUR_TO_GBP = 1.5678;
    private const GBP_TO_EUR = 1.5432;

    public function findBy(Currency $originalCurrency, Currency $targetCurrency): ?ExchangeRate
    {
        if (Currency::EUR === $originalCurrency && Currency::GBP === $targetCurrency) {
            return new ExchangeRate(Currency::EUR, Currency::GBP, self::EUR_TO_GBP);
        }

        if (Currency::GBP === $originalCurrency && Currency::EUR === $targetCurrency) {
            return new ExchangeRate(Currency::GBP, Currency::EUR, self::GBP_TO_EUR);
        }

        return null;
    }
}

<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Exception;

use App\CurrencyExchange\Domain\Enum\Currency;

class MoneyWithdrawalCalculatorException extends \DomainException
{
    static public function exchangeRateNotFound(Currency $originalCurrency, Currency $targetCurrency): self
    {
        return new self(sprintf(
            'No exchange rate found for original currency: %s and target currency: %s',
            $originalCurrency->value,
            $targetCurrency->value,
        ));
    }
}

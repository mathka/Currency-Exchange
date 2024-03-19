<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\ValueObject;

use App\CurrencyExchange\Domain\Enum\Currency;

class Money
{
    public function __construct(
        private readonly Currency $currency,
        private readonly int $amount,
    ) {
        if ($this->isNegativeNumber($this->amount)) {
            throw new \DomainException('The amount of the money object must be zero or greater than zero.');
        }
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function toString(): string
    {
        return sprintf('%s %s', $this->amount / 100, $this->currency->value);
    }

    private function isNegativeNumber(int $amount): bool
    {
        return 0 > $amount;
    }
}

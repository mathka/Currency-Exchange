<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Exception;

class PolicyException extends \DomainException
{
    static public function differentCurrencyInExchangedMoneyAndCommission(): self
    {
        return new self('The money after exchange and the commission must be in the same currency.');
    }

    static public function commissionIsGreaterThanExchangedMoney(): self
    {
        return new self('The commission amount should not exceed the amount after the currency exchange.');
    }
}

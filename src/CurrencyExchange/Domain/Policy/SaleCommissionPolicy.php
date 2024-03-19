<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Policy;

use App\CurrencyExchange\Domain\Exception\PolicyException;
use App\CurrencyExchange\Domain\Service\CommissionCalculator;
use App\CurrencyExchange\Domain\ValueObject\Money;

class SaleCommissionPolicy implements CommissionPolicy
{
    public function __construct(private readonly CommissionCalculator $commissionCalculator)
    {
    }

    public function apply(Money $exchangedMoney): Money
    {
        $commission = $this->commissionCalculator->calculate($exchangedMoney);

        $amountAfterSubCommission = $this->subCommissionFromExchangedMoney($exchangedMoney, $commission);
        if (0 > $amountAfterSubCommission) {
            throw PolicyException::commissionIsGreaterThanExchangedMoney();
        }

        return new Money(
            $exchangedMoney->getCurrency(),
            $amountAfterSubCommission,
        );
    }

    private function subCommissionFromExchangedMoney(Money $exchangedMoney, Money $commission): int
    {
        if ($exchangedMoney->getCurrency() !== $commission->getCurrency()) {
            throw PolicyException::differentCurrencyInExchangedMoneyAndCommission();
        }

        return $exchangedMoney->getAmount() - $commission->getAmount();
    }
}

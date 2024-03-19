<?php

declare(strict_types=1);

namespace App\CurrencyExchange\Domain\Service;

use App\CurrencyExchange\Domain\Entity\ExchangeRate;
use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\Exception\MoneyWithdrawalCalculatorException;
use App\CurrencyExchange\Domain\Policy\CommissionPolicy;
use App\CurrencyExchange\Domain\Repository\ExchangeRateRepository;
use App\CurrencyExchange\Domain\ValueObject\Money;

class MoneyWithdrawalCalculator
{
    public function __construct(
        private readonly ExchangeRateRepository $exchangeRateRepository,
        private readonly CurrencyExchangeCalculator $currencyExchangeCalculator,
        private readonly CommissionPolicy $commissionPolicy,
    ) {
    }

    public function calculate(Money $moneyPaidByCustomer, Currency $targetCurrency): Money
    {
        $exchangeRate = $this->getExchangeRate($moneyPaidByCustomer->getCurrency(), $targetCurrency);

        return $this->getExchangedMoneyIncludingCommission($moneyPaidByCustomer, $exchangeRate);
    }

    private function getExchangeRate(Currency $originalCurrency, Currency $targetCurrency): ExchangeRate
    {
        $exchangeRate = $this->exchangeRateRepository->findBy($originalCurrency, $targetCurrency);

        if (null == $exchangeRate) {
            throw MoneyWithdrawalCalculatorException::exchangeRateNotFound($originalCurrency, $targetCurrency);
        }

        return $exchangeRate;
    }

    private function getExchangedMoneyIncludingCommission(Money $moneyPaidByCustomer, ExchangeRate $exchangeRate): Money
    {
        $moneyAfterExchange = $this->currencyExchangeCalculator->calculate($moneyPaidByCustomer, $exchangeRate);

        return $this->commissionPolicy->apply($moneyAfterExchange);
    }
}

<?php

declare(strict_types=1);

namespace spec\App\CurrencyExchange\Domain\Service;

use App\CurrencyExchange\Domain\Entity\ExchangeRate;
use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\Exception\MoneyWithdrawalCalculatorException;
use App\CurrencyExchange\Domain\Policy\CommissionPolicy;
use App\CurrencyExchange\Domain\Repository\ExchangeRateRepository;
use App\CurrencyExchange\Domain\Service\CurrencyExchangeCalculator;
use App\CurrencyExchange\Domain\ValueObject\Money;
use PhpSpec\ObjectBehavior;

class MoneyWithdrawalCalculatorSpec extends ObjectBehavior
{
    public function let(
        ExchangeRateRepository $exchangeRateRepository,
        CurrencyExchangeCalculator $currencyExchangeCalculator,
        CommissionPolicy $commissionPolicy,
    ): void {
        $this->beConstructedWith($exchangeRateRepository, $currencyExchangeCalculator, $commissionPolicy);
    }

    public function it_returns_the_exchanged_money(
        ExchangeRateRepository $exchangeRateRepository,
        CurrencyExchangeCalculator $currencyExchangeCalculator,
        CommissionPolicy $commissionPolicy,
    ): void {
        //given
        $originalCurrency = Currency::GBP;
        $targetCurrency = Currency::EUR;
        $exchangeRate = new ExchangeRate($originalCurrency, $targetCurrency, 0.8543);
        $originalMoney = new Money($originalCurrency, 10000);
        $exchangedMoney = new Money($targetCurrency, 8543);
        $moneyWithAppliedCommission = new Money($targetCurrency, 8628);

        $exchangeRateRepository->findBy($originalCurrency, $targetCurrency)->willReturn($exchangeRate);
        $currencyExchangeCalculator->calculate($originalMoney, $exchangeRate)->willReturn($exchangedMoney);
        $commissionPolicy->apply($exchangedMoney)->willReturn($moneyWithAppliedCommission);

        //when
        $this->calculate($originalMoney, $targetCurrency)
        //then
            ->shouldBe($moneyWithAppliedCommission);
    }

    public function it_throws_an_exception_when_the_exchanged_rate_was_not_found(
        ExchangeRateRepository $exchangeRateRepository,
    ): void {
        //given
        $originalMoney = new Money(Currency::GBP, 10000);

        $exchangeRateRepository->findBy(Currency::GBP, Currency::EUR)->willReturn(null);

        //then
        $this->shouldThrow(MoneyWithdrawalCalculatorException::exchangeRateNotFound(Currency::GBP, Currency::EUR))
        //when
            ->during('calculate', [$originalMoney, Currency::EUR]);
    }
}

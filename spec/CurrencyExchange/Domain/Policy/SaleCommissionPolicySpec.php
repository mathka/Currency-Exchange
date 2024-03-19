<?php

declare(strict_types=1);

namespace spec\App\CurrencyExchange\Domain\Policy;

use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\Exception\PolicyException;
use App\CurrencyExchange\Domain\Service\CommissionCalculator;
use App\CurrencyExchange\Domain\ValueObject\Money;
use PhpSpec\ObjectBehavior;

class SaleCommissionPolicySpec extends ObjectBehavior
{
    public function let(CommissionCalculator $commissionCalculator)
    {
        $this->beConstructedWith($commissionCalculator);
    }

    public function it_returns_the_exchanged_money_minus_a_commission(
        CommissionCalculator $commissionCalculator,
    ): void {
        //given
        $exchangedMoney = new Money(Currency::GBP, 23476);
        $commission = new Money(Currency::GBP, 235);

        $commissionCalculator->calculate($exchangedMoney)->willReturn($commission);

        //when
        $this->apply($exchangedMoney)
        //then
            ->shouldBeLike(new Money(Currency::GBP, 23241));
    }

    public function it_throws_an_exception_when_the_exchanged_money_has_currency_different_from_the_commission(
        CommissionCalculator $commissionCalculator,
    ): void {
        //given
        $exchangedMoney = new Money(Currency::GBP, 23476);
        $commission = new Money(Currency::EUR, 235);

        $commissionCalculator->calculate($exchangedMoney)->willReturn($commission);

        //then
        $this->shouldThrow(PolicyException::differentCurrencyInExchangedMoneyAndCommission())
        //when
            ->during('apply', [$exchangedMoney]);
    }

    public function it_throws_an_exception_when_the_commission_is_greater_than_the_exchanged_money(
        CommissionCalculator $commissionCalculator,
    ): void {
        //given
        $exchangedMoney = new Money(Currency::GBP, 23476);
        $commission = new Money(Currency::EUR, 235);

        $commissionCalculator->calculate($exchangedMoney)->willReturn($commission);

        //then
        $this->shouldThrow(PolicyException::differentCurrencyInExchangedMoneyAndCommission())
        //when
            ->during('apply', [$exchangedMoney]);
    }
}

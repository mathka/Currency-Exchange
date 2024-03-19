<?php

declare(strict_types=1);

namespace spec\App\CurrencyExchange\Domain\Service;

use App\CurrencyExchange\Domain\Entity\ExchangeRate;
use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\ValueObject\Money;
use PhpSpec\ObjectBehavior;

class CurrencyExchangeCalculatorSpec extends ObjectBehavior
{
    public function it_returns_the_exchanged_money(): void
    {
        //given
        $originalMoney = new Money(Currency::GBP, 10000);
        $exchangeRate = new ExchangeRate(Currency::GBP, Currency::EUR, 0.8543);

        //when
        $this->calculate($originalMoney, $exchangeRate)
        //then
            ->shouldBeLike(new Money(Currency::EUR, 8543));
    }

    public function it_returns_the_exchanged_money_rounding_to_2_decimal_places(): void
    {
        //given
        $originalMoney = new Money(Currency::GBP, 100);
        $exchangeRate = new ExchangeRate(Currency::GBP, Currency::EUR, 0.8543);

        //when
        $this->calculate($originalMoney, $exchangeRate)
        //then
            ->shouldBeLike(new Money(Currency::EUR, 85));
    }

    public function it_returns_the_exchanged_money_rounding_up_if_the_next_digit_is_equal_to_5(): void
    {
        //given
        $originalMoney = new Money(Currency::GBP, 100);
        $exchangeRate = new ExchangeRate(Currency::GBP, Currency::EUR, 0.8555);

        //when
        $this->calculate($originalMoney, $exchangeRate)
        //then
            ->shouldBeLike(new Money(Currency::EUR, 86));
    }

    public function it_returns_the_exchanged_money_rounding_up_if_the_next_digit_is_greater_than_5(): void
    {
        //given
        $originalMoney = new Money(Currency::GBP, 100);
        $exchangeRate = new ExchangeRate(Currency::GBP, Currency::EUR, 0.8578);

        //when
        $this->calculate($originalMoney, $exchangeRate)
        //then
            ->shouldBeLike(new Money(Currency::EUR, 86));
    }
}

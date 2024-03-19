<?php

declare(strict_types=1);

namespace spec\App\CurrencyExchange\Domain\Service;

use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\ValueObject\Money;
use PhpSpec\ObjectBehavior;

class CommissionCalculatorSpec extends ObjectBehavior
{
    public function it_returns_the_commission(): void
    {
        //given
        $exchangedMoney = new Money(Currency::GBP, 23476);

        //when
        $this->calculate($exchangedMoney)
        //then
            ->shouldBeLike(new Money(Currency::GBP, 235));
    }
}

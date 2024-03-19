<?php

declare(strict_types=1);

namespace App\Tests\CurrencyExchange\Unit\Domain\Entity;

use App\CurrencyExchange\Domain\Entity\ExchangeRate;
use App\CurrencyExchange\Domain\Enum\Currency;
use PHPUnit\Framework\TestCase;

class ExchangeRateTest extends TestCase
{
    public function testCreateExchangeRate(): void
    {
        //when
        $exchangeRate = new ExchangeRate(Currency::EUR, Currency::GBP, 1.1558);

        //then
        self::assertInstanceOf(ExchangeRate::class, $exchangeRate);
        self::assertEquals(1.1558, $exchangeRate->getRate());
        self::assertEquals(Currency::GBP, $exchangeRate->getTargetCurrency());
    }

    public function testThrowExceptionWhenTryingToCreateAnExchangeRateWithTwoOfTheSameCurrency(): void
    {
        //then
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('The exchange rate must contain two different currencies');

        //when
        new ExchangeRate(Currency::EUR, Currency::EUR, 1.1558);
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\CurrencyExchange\Unit\Domain\ValueObject;

use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @dataProvider dataToCreateAValidMoneyObject
     */
    public function testCreateMoney(int $amount, Currency $currency): void
    {
        //when
        $money = new Money($currency, $amount);

        //then
        self::assertInstanceOf(Money::class, $money);
        self::assertEquals($amount, $money->getAmount());
        self::assertEquals($currency, $money->getCurrency());
    }

    /**
     * @return array<string, array<string, Currency|int>>
     */
    public function dataToCreateAValidMoneyObject(): iterable
    {
        yield 'Amount above zero' => [
            'amount' => 15048,
            'currency' => Currency::EUR,
        ];

        yield 'Amount equal to zero' => [
            'amount' => 0,
            'currency' => Currency::EUR,
        ];
    }

    /**
     * @dataProvider dataToAttemptToCreateAnInvalidMoneyObject
     */
    public function testThrowExceptionWhenTryToCreateMoneyWithAmountLowerThanZero(int $amount, Currency $currency): void
    {
        //then
        self::expectException(\DomainException::class);
        self::expectExceptionMessage('The amount of the money object must be zero or greater than zero.');

        //when
        new Money($currency, $amount);
    }

    /**
     * @return array<string, array<string, Currency|int>>
     */
    public function dataToAttemptToCreateAnInvalidMoneyObject(): iterable
    {
        yield 'Amount just below zero' => [
            'amount' => -1,
            'currency' => Currency::EUR,
        ];

        yield 'Amount below zero' => [
            'amount' => -10000,
            'currency' => Currency::EUR,
        ];
    }

    /**
     * @dataProvider dataToCreateAValidMoneyObject
     */
    public function testReturnString(): void
    {
        //given
        $money = new Money(Currency::EUR, 15487);

        //when
        $moneyAsString = $money->toString();

        //then
        self::assertEquals('154.87 EUR', $moneyAsString);
    }
}

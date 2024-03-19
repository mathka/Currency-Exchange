<?php

declare(strict_types=1);

namespace App\Tests\CurrencyExchange\Integration;

use App\CurrencyExchange\Domain\Enum\Currency;
use App\CurrencyExchange\Domain\Policy\PurchaseCommissionPolicy;
use App\CurrencyExchange\Domain\Policy\SaleCommissionPolicy;
use App\CurrencyExchange\Domain\Service\CommissionCalculator;
use App\CurrencyExchange\Domain\Service\CurrencyExchangeCalculator;
use App\CurrencyExchange\Domain\Service\MoneyWithdrawalCalculator;
use App\CurrencyExchange\Domain\ValueObject\Money;
use App\CurrencyExchange\Infrastructure\Repository\ExchangeRateRepository;
use PHPUnit\Framework\TestCase;

class CurrencyExchangeTest extends TestCase
{
    private ExchangeRateRepository $exchangeRateRepository;
    private CurrencyExchangeCalculator $currencyExchangeCalculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exchangeRateRepository = new ExchangeRateRepository();
        $this->currencyExchangeCalculator = new CurrencyExchangeCalculator();
    }

    /**
     * @dataProvider dataForVerifyingCurrencySales
     */
    public function testCalculationForCurrencySales(
        int $amount,
        Currency $originalCurrency,
        Currency $targetCurrency,
        string $expectedResult,
    ): void {
        //given
        $moneyWithdrawalCalculator = new MoneyWithdrawalCalculator(
            $this->exchangeRateRepository,
            $this->currencyExchangeCalculator,
            new SaleCommissionPolicy(new CommissionCalculator())
        );
        $moneySold = new Money($originalCurrency, $amount);

        //when
        $exchangedMoney = $moneyWithdrawalCalculator->calculate($moneySold, $targetCurrency);

        //then
        self::assertInstanceOf(Money::class, $exchangedMoney);
        self::assertEquals($expectedResult, $exchangedMoney->toString());
    }

    /**
     * @return array<string, mixed>
     */
    public function dataForVerifyingCurrencySales(): iterable
    {
        yield 'Klient sprzedaje 100 EUR za GBP' => [
            'amount' => 10000,
            'originalCurrency' => Currency::EUR,
            'targetCurrency' => Currency::GBP,
            'expectedResult' => '155.21 GBP',
        ];

        yield 'Klient sprzedaje 100 GBP za EUR' => [
            'amount' => 10000,
            'originalCurrency' => Currency::GBP,
            'targetCurrency' => Currency::EUR,
            'expectedResult' => '152.78 EUR',
        ];
    }

    /**
     * @dataProvider dataForVerifyingCurrencyPurchases
     */
    public function testCalculationForCurrencyPurchases(
        int $amount,
        Currency $originalCurrency,
        Currency $targetCurrency,
        string $expectedResult,
    ): void {
        //given
        $moneyWithdrawalCalculator = new MoneyWithdrawalCalculator(
            $this->exchangeRateRepository,
            $this->currencyExchangeCalculator,
            new PurchaseCommissionPolicy(new CommissionCalculator())
        );
        $moneyPurchased = new Money($originalCurrency, $amount);

        //when
        $exchangedMoney = $moneyWithdrawalCalculator->calculate($moneyPurchased, $targetCurrency);

        //then
        self::assertInstanceOf(Money::class, $exchangedMoney);
        self::assertEquals($expectedResult, $exchangedMoney->toString());
    }

    /**
     * @return array<string, mixed>
     */
    public function dataForVerifyingCurrencyPurchases(): iterable
    {
        yield 'Klient kupuje 100 GBP za EUR' => [
            'amount' => 10000,
            'originalCurrency' => Currency::GBP,
            'targetCurrency' => Currency::EUR,
            'expectedResult' => '155.86 EUR',
        ];

        yield 'Klient kupuje 100 EUR za GBP' => [
            'amount' => 10000,
            'originalCurrency' => Currency::EUR,
            'targetCurrency' => Currency::GBP,
            'expectedResult' => '158.35 GBP',
        ];
    }
}

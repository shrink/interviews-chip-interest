<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Shrink\Chip\Interest\Account;
use Shrink\Chip\Interest\Transaction;
use Shrink\Chip\Interest\UserId;

final class AccountTest extends TestCase
{
    /**
     * @test
     */
    public function AccountInterestRateMustBePositive(): void
    {
        $this->expectException(RuntimeException::class);

        new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            -100,
            []
        );
    }

    /**
     * @test
     */
    public function AccountHasInterestRate(): void
    {
        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            100,
            []
        );

        $this->assertSame(100, $account->interestRate());
    }

    /**
     * @test
     */
    public function AccountMustNotAcceptInvalidTransactionValues(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0,
            [1]
        );
    }

    /**
     * @test
     */
    public function AccountHasTransactionHistory(): void
    {
        $transactions = [
            new Transaction(10, new DateTimeImmutable()),
            new Transaction(20, new DateTimeImmutable()),
        ];

        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0,
            $transactions
        );

        $this->assertSame($transactions, $account->transactions());
    }

    /**
     * @test
     */
    public function AccountHasBalanceDerivedFromTransactions(): void
    {
        $transactions = [
            new Transaction(20, new DateTimeImmutable()),
            new Transaction(-10, new DateTimeImmutable()),
            new Transaction(90, new DateTimeImmutable()),
        ];

        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0,
            $transactions
        );

        $this->assertSame(100, $account->balance());
    }

    /**
     * @test
     */
    public function AccountWithoutTransactionsHasZeroBalance(): void
    {
        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0,
            []
        );

        $this->assertSame(0, $account->balance());
    }

    /**
     * @test
     */
    public function NewTransactionChangesBalance(): void
    {
        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0,
            [new Transaction(10, new DateTimeImmutable())],
        );

        $account->recordTransaction(
            new Transaction(90, new DateTimeImmutable())
        );

        $this->assertSame(100, $account->balance());
    }
}

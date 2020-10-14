<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Shrink\Chip\Interest\Transaction;

final class TransactionTest extends TestCase
{
    /**
     * @test
     */
    public function TransactionHasAmountInPennies(): void
    {
        $transaction = new Transaction(100, new DateTimeImmutable());

        $this->assertSame(100, $transaction->amount());
    }

    /**
     * @test
     */
    public function TransactionAmountCanBeNegative(): void
    {
        $transaction = new Transaction(-100, new DateTimeImmutable());

        $this->assertSame(-100, $transaction->amount());
    }

    /**
     * @test
     */
    public function TransactionIsCreatedAtTime(): void
    {
        $createdAt = new DateTimeImmutable('2018-01-01 00:00:00');

        $transaction = new Transaction(0, $createdAt);

        $this->assertSame($createdAt, $transaction->createdAt());
    }
}

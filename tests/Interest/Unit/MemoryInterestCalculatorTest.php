<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use DateTimeImmutable;
use Lcobucci\Clock\Clock;
use PHPUnit\Framework\TestCase;
use Shrink\Chip\Interest\Account;
use Shrink\Chip\Interest\MemoryInterestCalculator;
use Shrink\Chip\Interest\Transaction;
use Shrink\Chip\Interest\UserId;

final class MemoryInterestCalculatorTest extends TestCase
{
    /**
     * List of interest amounts and the expected awards.
     *
     * @return array<string,<float,float,float>>
     */
    public function interestAwardAmounts(): array
    {
        return [
            'No award for negative balance' => [100, -1000, -1000],
            'No award for zero balance' => [100, 0, 0],
            'No award for less than a penny' => [1, 100, 100],
            'Awarded for more than a penny earned during period' => [100, 1000_00, 1001_64],
        ];
    }

    /**
     * @test
     * @dataProvider interestAwardAmounts
     */
    public function InterestIsAwardedToAccount(
        int $rate,
        int $startingBalance,
        int $expectedBalance
    ): void {
        $memoryInterestCalculator = new MemoryInterestCalculator(
            60,
            $this->createMock(Clock::class)
        );

        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            $rate,
            [
                new Transaction($startingBalance, new DateTimeImmutable())
            ]
        );

        $memoryInterestCalculator->awardEarnedInterest($account);

        $this->assertSame($expectedBalance, $account->balance());
    }

    /**
     * @test
     */
    public function SmallEarningsForPeriodAccumulateForASingleAward(): void
    {
        $memoryInterestCalculator = new MemoryInterestCalculator(
            100,
            $this->createMock(Clock::class)
        );

        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            200,
            [
                new Transaction(100, new DateTimeImmutable())
            ]
        );

        $memoryInterestCalculator->awardEarnedInterest($account);
        $memoryInterestCalculator->awardEarnedInterest($account);

        $this->assertCount(2, $account->transactions());
        $this->assertSame(101, $account->balance());
    }

    /**
     * @test
     */
    public function InterestIsAwardedAtCurrentTime(): void
    {
        $now = new DateTimeImmutable('2018-01-01 00:00:00');

        ($clock = $this->createMock(Clock::class))
            ->method('now')
            ->willReturn($now);

        $memoryInterestCalculator = new MemoryInterestCalculator(100, $clock);

        $account = new Account(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            200,
            [
                new Transaction(100000, new DateTimeImmutable())
            ]
        );

        $memoryInterestCalculator->awardEarnedInterest($account);

        $latestTransaction = array_slice($account->transactions(), -1, 1)[0];

        $this->assertEquals($now, $latestTransaction->createdAt());
    }
}

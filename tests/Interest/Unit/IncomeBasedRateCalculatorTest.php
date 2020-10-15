<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use PHPUnit\Framework\TestCase;
use Shrink\Chip\Interest\IncomeBasedRate;
use Shrink\Chip\Interest\User;
use Shrink\Chip\Interest\UserId;

final class IncomeBasedRateTest extends TestCase
{
    /**
     * @test
     */
    public function UserWithoutIncomeInformationReceivesDefaultRate(): void
    {
        $user = new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            null
        );

        $incomeBasedRate = new IncomeBasedRate(50, []);

        $userRate = $incomeBasedRate->interestRateForUser($user);

        $this->assertSame(50, $userRate);
    }

    /**
     * @test
     */
    public function UserReceivesHighestRateAvailableForTheirIncome(): void
    {
        $user = new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            3000
        );

        $incomeBasedRate = new IncomeBasedRate(50, [
            1000 => 100,
            2000 => 200,
            3000 => 300,
            4000 => 400,
        ]);

        $userRate = $incomeBasedRate->interestRateForUser($user);

        $this->assertSame(300, $userRate);
    }

    /**
     * @test
     */
    public function UserReceivesDefaultRateAvailableIfBelowLowestIncome(): void
    {
        $user = new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            500
        );

        $incomeBasedRate = new IncomeBasedRate(50, [
            1000 => 100,
            2000 => 200,
            3000 => 300,
            4000 => 400,
        ]);

        $userRate = $incomeBasedRate->interestRateForUser($user);

        $this->assertSame(50, $userRate);
    }
}

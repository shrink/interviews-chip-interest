<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use RuntimeException;
use PHPUnit\Framework\TestCase;
use Shrink\Chip\Interest\User;
use Shrink\Chip\Interest\UserId;

final class UserTest extends TestCase
{
    /**
     * @test
     */
    public function UserHasId(): void
    {
        $user = new User(
            $id = new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0
        );

        $this->assertSame($id, $user->id());
    }

    /**
     * @test
     */
    public function UserHasIncomeInformationWhenProvided(): void
    {
        $user = new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            100
        );

        $this->assertTrue($user->hasIncomeInformation());
        $this->assertSame(100, $user->incomePerMonth());
    }

    /**
     * @test
     */
    public function UserHasNoIncomeInformationWhenNotProvided(): void
    {
        $user = new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            null
        );

        $this->assertFalse($user->hasIncomeInformation());
    }

    /**
     * @test
     */
    public function IncomeInformationCannotBeRetrievedWhenItDoesNotExist(): void
    {
        $this->expectException(RuntimeException::class);

        $user = new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            null
        );

        $user->incomePerMonth();
    }

    /**
     * @test
     */
    public function UserCanHaveZeroIncome(): void
    {
        $user = new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0
        );

        $this->assertTrue($user->hasIncomeInformation());
        $this->assertSame(0, $user->incomePerMonth());
    }

    /**
     * @test
     */
    public function UserIncomeMustBeAPositiveAmount(): void
    {
        $this->expectException(RuntimeException::class);

        new User(
            new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            -100
        );
    }
}

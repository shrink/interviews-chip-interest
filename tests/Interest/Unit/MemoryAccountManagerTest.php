<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Shrink\Chip\Interest\Account;
use Shrink\Chip\Interest\CalculatesInterestRates;
use Shrink\Chip\Interest\MemoryAccountManager;
use Shrink\Chip\Interest\User;
use Shrink\Chip\Interest\UserId;

final class MemoryAccountManagerTest extends TestCase
{
    /**
     * @test
     */
    public function AccountIsFoundByUserId(): void
    {
        $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95');
        $account = new Account(100, []);

        $memoryAccountManager = new MemoryAccountManager([
            (string) $userId => $account,
        ], $this->createMock(CalculatesInterestRates::class));

        $this->assertTrue($memoryAccountManager->userHasInterestAccount($userId));
        $this->assertSame($account, $memoryAccountManager->interestAccountByUserId($userId));
    }

    /**
     * @test
     */
    public function UserDoesNotHaveAccountWhenNotFound(): void
    {
        $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95');
        $memoryAccountManager = new MemoryAccountManager(
            [],
            $this->createMock(CalculatesInterestRates::class)
        );

        $this->assertFalse($memoryAccountManager->userHasInterestAccount($userId));
    }

    /**
     * @test
     */
    public function AccountCannotBeRetrievedForUserWithoutAccount(): void
    {
        $this->expectException(RuntimeException::class);

        $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95');
        $memoryAccountManager = new MemoryAccountManager(
            [],
            $this->createMock(CalculatesInterestRates::class)
        );

        $memoryAccountManager->interestAccountByUserId($userId);
    }

    /**
     * @test
     */
    public function AccountCannotBeCreatedForUserWithAccount(): void
    {
        $this->expectException(RuntimeException::class);

        $user = new User(
            $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0
        );

        $account = new Account(100, []);

        $memoryAccountManager = new MemoryAccountManager([
            (string) $userId => $account,
        ], $this->createMock(CalculatesInterestRates::class));

        $memoryAccountManager->openInterestAccount($user);
    }

    /**
     * @test
     */
    public function AccountIsCreatedWithCalculatedRate(): void
    {
        $user = new User(
            $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95'),
            0
        );

        ($interestRates = $this->createMock(CalculatesInterestRates::class))
            ->method('interestRateForUser')
            ->with($user)
            ->willReturn(50);


        $memoryAccountManager = new MemoryAccountManager([], $interestRates);

        $expectedAccount = new Account(50, []);

        $memoryAccountManager->openInterestAccount($user);

        $this->assertEquals(
            $expectedAccount,
            $memoryAccountManager->interestAccountByUserId($userId)
        );
    }
}

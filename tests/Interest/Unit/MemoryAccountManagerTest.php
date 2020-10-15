<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Shrink\Chip\Interest\Account;
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
        ]);

        $this->assertTrue($memoryAccountManager->userHasInterestAccount($userId));
        $this->assertSame($account, $memoryAccountManager->interestAccountByUserId($userId));
    }

    /**
     * @test
     */
    public function UserDoesNotHaveAccountWhenNotFound(): void
    {
        $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95');
        $memoryAccountManager = new MemoryAccountManager([]);

        $this->assertFalse($memoryAccountManager->userHasInterestAccount($userId));
    }

    /**
     * @test
     */
    public function AccountCannotBeRetrievedForUserWithoutAccount(): void
    {
        $this->expectException(RuntimeException::class);

        $userId = new UserId('88224979-406e-4e32-9458-55836e4e1f95');
        $memoryAccountManager = new MemoryAccountManager([]);

        $memoryAccountManager->interestAccountByUserId($userId);
    }
}

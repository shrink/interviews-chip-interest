<?php

declare(strict_types=1);

namespace Tests\Chip\Interest\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Shrink\Chip\Interest\UserId;

final class UserIdTest extends TestCase
{
    /**
     * @test
     */
    public function UserIdAcceptsValidUuidV4(): void
    {
        $uuid = '88224979-406e-4e32-9458-55836e4e1f95';

        $userId = new UserId($uuid);

        $this->assertSame($uuid, (string) $userId);
    }

    /**
     * Create a list of invalid User IDs.
     *
     * @return array<string<array<string>>>
     */
    public function invalidUserIds(): array
    {
        return [
            'Wrong UUID version' => ['123e4567-e89b-12d3-a456-426614174000'],
            'Non-uuid string' => ['invalid-string'],
            'Correct length but invalid value' => ['88224979-406e-4__2-9458-55836e4e1f95'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidUserIds
     */
    public function UserIdDoesNotAcceptInvalidId(string $invalidId): void
    {
        $this->expectException(InvalidArgumentException::class);

        new UserId($invalidId);
    }
}

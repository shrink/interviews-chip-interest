<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use InvalidArgumentException;
use function preg_match;
use function str_replace;

final class UserId
{
    /**
     * A user is identified by a UUID (Version 4).
     */
    private string $id;

    public function __construct(string $id)
    {
        $validUuidV4Pattern = str_replace(
            '%hex',
            '0-9a-f',
            '[%hex]{8}\-[%hex]{4}\-4[%hex]{3}\-[89ab][%hex]{3}\-[%hex]{12}'
        );

        if (! preg_match("%{$validUuidV4Pattern}%i", $id)) {
            throw new InvalidArgumentException("{$id} is not a valid UUID v4");
        }

        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}

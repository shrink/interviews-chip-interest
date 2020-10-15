<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use RuntimeException;
use function array_key_exists;

final class MemoryAccountManager implements ProvidesInterestAccounts
{
    /**
     * @var array<string,\Shrink\Chip\Interest\Account>
     */
    private array $accounts;

    /**
     * @param array<string,\Shrink\Chip\Interest\Account> $accounts
     */
    public function __construct(array $accounts)
    {
        $this->accounts = $accounts;
    }

    public function userHasInterestAccount(UserId $id): bool
    {
        return array_key_exists((string) $id, $this->accounts);
    }

    public function interestAccountByUserId(UserId $id): Account
    {
        if (! $this->userHasInterestAccount($id)) {
            throw new RuntimeException(
                "{$id} does not have an interest account."
            );
        }

        return $this->accounts[(string) $id];
    }
}

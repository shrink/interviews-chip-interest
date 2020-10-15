<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use RuntimeException;
use function array_key_exists;

final class MemoryAccountManager implements
    ProvidesInterestAccounts,
    OpensInterestAccounts
{
    /**
     * @var array<string,\Shrink\Chip\Interest\Account>
     */
    private array $accounts;

    private CalculatesInterestRates $rates;

    /**
     * @param array<string,\Shrink\Chip\Interest\Account> $accounts
     */
    public function __construct(array $accounts, CalculatesInterestRates $rates)
    {
        $this->accounts = $accounts;
        $this->rates = $rates;
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

    public function openInterestAccount(User $user): void
    {
        if ($this->userHasInterestAccount($user->id())) {
            throw new RuntimeException(
                "{$user->id()} already has an interest account."
            );
        }

        $account = new Account(
            $this->rates->interestRateForUser($user),
            []
        );

        $this->accounts[(string) $user->id()] = $account;
    }
}

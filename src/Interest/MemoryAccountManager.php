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

    private ProvidesUserInformation $users;

    /**
     * @param array<string,\Shrink\Chip\Interest\Account> $accounts
     */
    public function __construct(
        array $accounts,
        CalculatesInterestRates $rates,
        ProvidesUserInformation $users
    ) {
        $this->accounts = $accounts;
        $this->rates = $rates;
        $this->users = $users;
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

    public function openInterestAccount(UserId $id): void
    {
        if ($this->userHasInterestAccount($id)) {
            throw new RuntimeException(
                "{$id} already has an interest account."
            );
        }

        $user = $this->users->userById($id);

        $account = new Account(
            $this->rates->interestRateForUser($user),
            []
        );

        $this->accounts[(string) $user->id()] = $account;
    }
}

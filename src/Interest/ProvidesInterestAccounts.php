<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

interface ProvidesInterestAccounts
{
    public function userHasInterestAccount(UserId $id): bool;

    public function interestAccountByUserId(UserId $id): Account;
}

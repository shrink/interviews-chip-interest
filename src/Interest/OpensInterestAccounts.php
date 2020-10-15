<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

interface OpensInterestAccounts
{
    public function openInterestAccount(User $user): void;
}

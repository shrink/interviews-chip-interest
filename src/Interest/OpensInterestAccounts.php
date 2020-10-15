<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

interface OpensInterestAccounts
{
    public function openInterestAccount(UserId $id): void;
}

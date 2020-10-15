<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

interface AwardsInterest
{
    public function awardEarnedInterest(Account $account): void;
}

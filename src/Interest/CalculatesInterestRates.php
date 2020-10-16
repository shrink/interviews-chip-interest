<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

interface CalculatesInterestRates
{
    public function interestRateForUser(User $user): int;
}

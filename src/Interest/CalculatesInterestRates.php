<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

interface CalculatesInterestRates
{
    /**
     * Calculate interest rate for user.
     */
    public function interestRateForUser(User $user): int;
}

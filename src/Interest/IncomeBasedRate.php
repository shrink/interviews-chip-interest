<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

final class IncomeBasedRate implements CalculatesInterestRates
{
    /**
     * Default rate when a user does not have income information, or the user
     * is not eligible for any of the supported rates.
     */
    private int $default;

    /**
     * An ordered list of rates using a key to identify the minimum amount
     * required to be eligible for this interest rate.
     *
     * @var array<int,int>
     */
    private array $rates;

    /**
     * @param array<int,int> $rates
     */
    public function __construct(int $default, array $rates)
    {
        $this->default = $default;
        $this->rates = $rates;
    }

    public function interestRateForUser(User $user): int
    {
        $userRate = $this->default;

        if (! $user->hasIncomeInformation()) {
            return $userRate;
        }

        foreach ($this->rates as $minimum => $interestRate) {
            if ($user->incomePerMonth() >= $minimum) {
                $userRate = $interestRate;
            }
        }

        return $userRate;
    }
}

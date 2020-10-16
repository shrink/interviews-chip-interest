<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use Lcobucci\Clock\Clock;

final class MemoryInterestCalculator implements AwardsInterest
{
    /**
     * Number of days to award interest for.
     */
    private int $awardFrequency;

    /**
     * @var array<string,float>
     */
    private array $pendingAwards = [];

    private Clock $clock;

    public function __construct(int $awardFrequency, Clock $clock)
    {
        $this->awardFrequency = $awardFrequency;
        $this->clock = $clock;
    }

    /**
     * The total award amount is calculated using the amount earned for this
     * period and any pending amounts. The total award amount must exceed one
     * penny for the award to be granted, otherwise it is recorded as pending.
     */
    public function awardEarnedInterest(Account $account): void
    {
        $userId = (string) $account->userId();
        $amount = $this->amountEarnedForPeriod($account);

        $award = ($this->pendingAwards[$userId] ?? 0) + $amount;

        if ($award > 0 && $award < 1) {
            $this->pendingAwards[$userId] = $award;
            return;
        }

        $account->recordTransaction(
            new Transaction((int) $award, $this->clock->now())
        );
    }

    private function amountEarnedForPeriod(Account $account): float
    {
        $dailyInterestAmount = $this->dailyInterestAmount(
            $account->balance(),
            $account->interestRate()
        );

        return $dailyInterestAmount * $this->awardFrequency;
    }

    private function dailyInterestAmount(int $balance, int $interestRate): float
    {
        $eligibleBalance = max(0, $balance);

        $dailyInterestPercentage = $interestRate / 10_000 / 365;

        return $eligibleBalance * $dailyInterestPercentage;
    }
}

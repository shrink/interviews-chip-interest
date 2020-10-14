<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use RuntimeException;

final class User
{
    private UserId $id;

    /**
     * Income per month in pennies.
     */
    private ?int $incomePerMonth;

    public function __construct(UserId $id, ?int $incomePerMonth)
    {
        if (! is_null($incomePerMonth) && $incomePerMonth < 0) {
            throw new RuntimeException(
                "A user income must be positive, {$incomePerMonth} is invalid."
            );
        }

        $this->id = $id;
        $this->incomePerMonth = $incomePerMonth;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function hasIncomeInformation(): bool
    {
        return is_int($this->incomePerMonth);
    }

    /**
     * @throws \RuntimeException
     */
    public function incomePerMonth(): int
    {
        if (! $this->hasIncomeInformation()) {
            throw new RuntimeException(
                "{$this->id()} has not provided income information."
            );
        }

        return (int) $this->incomePerMonth;
    }
}

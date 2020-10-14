<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use DateTimeInterface;

final class Transaction
{
    /**
     * Amount in pennies (positive or negative).
     */
    private int $amount;

    private DateTimeInterface $createdAt;

    public function __construct(int $amount, DateTimeInterface $createdAt)
    {
        $this->amount = $amount;
        $this->createdAt = $createdAt;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function createdAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}

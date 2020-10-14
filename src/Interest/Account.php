<?php

declare(strict_types=1);

namespace Shrink\Chip\Interest;

use InvalidArgumentException;
use RuntimeException;
use function array_filter;
use function array_push;
use function array_reduce;
use function is_a;

final class Account
{
    /**
     * Interest rate as a whole number, representing percentages to two decimal
     * places (from 0 (0.00%) to 10000 (100.00%)).
     */
    private int $interestRate;

    /**
     * @var array<\Shrink\Chip\Interest\Transaction>
     */
    private array $transactions;

    /**
     * @param array<\Shrink\Chip\Interest\Transaction> $transactions
     */
    public function __construct(int $interestRate, array $transactions)
    {
        $this->guardTransactionTypes($transactions);

        if ($interestRate < 0) {
            throw new RuntimeException(
                "Account interest rate must be positive, got: {$interestRate}."
            );
        }

        $this->interestRate = $interestRate;
        $this->transactions = $transactions;
    }

    public function interestRate(): int
    {
        return $this->interestRate;
    }

    /**
     * @return array<\Shrink\Chip\Interest\Transaction>
     */
    public function transactions(): array
    {
        return $this->transactions;
    }

    public function balance(): int
    {
        $runningBalance = static function (
            int $balance,
            Transaction $transaction
        ): int {
            return $balance + $transaction->amount();
        };

        return array_reduce($this->transactions, $runningBalance, 0);
    }

    public function recordTransaction(Transaction $transaction): void
    {
        array_push($this->transactions, $transaction);
    }

    /**
     * @param array<\Shrink\Chip\Interest\Transaction> $values
     */
    private function guardTransactionTypes(array $values): void
    {
        $isTransaction =
            /** @param object|string $value */
            static function ($value): bool {
                return is_a($value, Transaction::class);
            };

        if (array_filter($values, $isTransaction) !== $values) {
            throw new InvalidArgumentException(
                'Account transactions must be instances of Transaction.'
            );
        }
    }
}

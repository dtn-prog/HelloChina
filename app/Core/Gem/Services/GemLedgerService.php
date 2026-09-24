<?php

namespace App\Core\Gem\Services;

use App\Core\Gem\Exceptions\InsufficientGemsException;
use App\Core\Gem\Models\GemTransaction;
use App\Core\User\Models\UserStat;
use App\Models\User;
use App\Support\Enums\GemTransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class GemLedgerService
{
    public function credit(
        User $user,
        int $amount,
        GemTransactionType $type,
        ?Model $reference = null,
    ): GemTransaction {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Credit amount must be greater than zero.');
        }

        return $this->record($user, $amount, $type, $reference);
    }

    public function debit(
        User $user,
        int $amount,
        GemTransactionType $type,
        ?Model $reference = null,
    ): GemTransaction {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Debit amount must be greater than zero.');
        }

        return $this->record($user, -$amount, $type, $reference);
    }

    public function adjust(
        User $user,
        int $amount,
        GemTransactionType $type = GemTransactionType::AdminAdjustment,
        ?Model $reference = null,
    ): GemTransaction {
        if ($amount === 0) {
            throw new InvalidArgumentException('Adjustment amount cannot be zero.');
        }

        return $this->record($user, $amount, $type, $reference);
    }

    public function balance(User $user): int
    {
        return (int) $user->ensureStats()->gems;
    }

    public function ledgerBalance(User $user): int
    {
        return (int) GemTransaction::query()
            ->where('user_id', $user->id)
            ->sum('amount');
    }

    /**
     * Append an immutable ledger row and keep user_stats.gems in sync.
     * Balance never goes below zero.
     */
    public function record(
        User $user,
        int $amount,
        GemTransactionType $type,
        ?Model $reference = null,
    ): GemTransaction {
        if ($amount === 0) {
            throw new InvalidArgumentException('Ledger amount cannot be zero.');
        }

        return DB::transaction(function () use ($user, $amount, $type, $reference) {
            $stats = UserStat::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (! $stats) {
                $user->ensureStats();
                $stats = UserStat::query()
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->firstOrFail();
            }

            $balance = (int) $stats->gems;
            $nextBalance = $balance + $amount;

            if ($nextBalance < 0) {
                throw new InsufficientGemsException(abs($amount), $balance);
            }

            $transaction = GemTransaction::query()->create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => $type,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->getKey(),
            ]);

            $stats->update(['gems' => $nextBalance]);
            $user->unsetRelation('stats');

            return $transaction;
        });
    }
}

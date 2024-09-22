<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $transaction->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Transaction $transaction, Category $category, Account $account): bool
    {
        return $this->isUniqueIds($user->id, $transaction->user_id, $account->user_id, $category->user_id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transaction $transaction, Category $category, Account $account): bool
    {
        return $this->isUniqueIds($user->id, $transaction->user_id, $account->user_id, $category->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $transaction->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Transaction $transaction): bool
    {
        return $transaction->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return $transaction->user_id === $user->id;
    }
}
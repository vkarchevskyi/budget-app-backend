<?php

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->account = Account::factory()->create(['user_id' => $this->user->id]);
    $this->incomeCategory = Category::factory()->create(['user_id' => $this->user->id]);
    $this->expensesCategory = Category::factory()->create(['user_id' => $this->user->id]);
});

test('balance changing after adding new transactions', function () {
    expect($this->account->balance)->toBe(0);

    $transactions = Transaction::factory(10)->create([
        'account_id' => $this->account->id,
        'category_id' => random_int(0, 1) ? $this->incomeCategory->id : $this->expensesCategory->id,
    ]);

    $balance = $transactions->sum(function ($transaction) {
        return $transaction->price * ($transaction->category->is_income ? 1 : -1);
    });

    expect($this->account->balance)->toBe($balance);
});

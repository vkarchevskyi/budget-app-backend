<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        /** @var Collection<int, Account> $accounts */
        $accounts = Account::factory(2)
            ->state(['user_id' => $testUser->id])
            ->sequence(['name' => 'Cash'], ['name' => 'Card'])
            ->create();

        /** @var Collection<int, Category> $categories */
        $categories = Category::factory(5)
            ->state(['user_id' => $testUser->id])
            ->sequence(
                ['name' => 'Bitcoin', 'is_income' => true],
                ['name' => 'Cinema', 'is_income' => false],
                ['name' => 'Groceries', 'is_income' => false],
                ['name' => 'Books', 'is_income' => false],
                ['name' => 'Salary', 'is_income' => true],
            )
            ->create();

        Transaction::factory(1000)
            ->state(
                new Sequence(
                    fn (Sequence $sequence): array => [
                        'user_id' => $testUser->id,
                        'account_id' => $accounts->random()->id,
                        'category_id' => $categories->random()->id,
                    ]
                )
            )
            ->create();
    }
}

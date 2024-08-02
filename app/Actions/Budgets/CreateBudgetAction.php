<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\CreateBudgetDTO;
use App\Models\Budget;
use Illuminate\Support\Facades\DB;

class CreateBudgetAction
{
    /**
     * @throws \Throwable
     */
    public function run(CreateBudgetDTO $createBudgetDTO): void
    {
        DB::transaction(function () use ($createBudgetDTO): void {
            Budget::query()
                ->create([
                    'size' => $createBudgetDTO->size,
                    'category_id' => $createBudgetDTO->categoryId,
                    'user_id' => $createBudgetDTO->userId,
                    'date' => $createBudgetDTO->date,
                ]);
        });
    }
}

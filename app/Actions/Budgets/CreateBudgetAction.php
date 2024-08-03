<?php

declare(strict_types=1);

namespace App\Actions\Budgets;

use App\DTO\Budgets\CreateBudgetDTO;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreateBudgetAction
{
    /**
     * @throws Throwable
     */
    public function run(CreateBudgetDTO $createBudgetDTO): Budget
    {
        return DB::transaction(function () use ($createBudgetDTO): Budget {
            $userOwnsCategory = Category::query()
                ->where('id', '=', $createBudgetDTO->categoryId)
                ->where('user_id', '=', auth()->user()?->getAuthIdentifier())
                ->exists();

            if ($userOwnsCategory) {
                abort(403);
            }

            return Budget::query()
                ->create([
                    'size' => $createBudgetDTO->size,
                    'category_id' => $createBudgetDTO->categoryId,
                    'user_id' => auth()->user()?->getAuthIdentifier(),
                    'date' => $createBudgetDTO->date,
                ]);
        });
    }
}

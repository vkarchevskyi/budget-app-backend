<?php

declare(strict_types=1);

namespace App\ViewModels\Charts;

use App\DTO\Charts\ExpensesDTO;
use App\Models\Transaction;
use App\Resources\Charts\ExpenseResource;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExpensesViewModel
{
    /**
     * @param ExpensesDTO $expensesData
     * @return Collection<int, ExpenseResource>
     */
    public function get(ExpensesDTO $expensesData): Collection
    {
        $startDate = now()->subMonths($expensesData->monthQuantity)->startOfMonth()->addMonth();
        $withDifferentYears = now()->diff($startDate)->y >= 1;

        return Transaction::query()
            ->selectRaw("EXTRACT(YEAR FROM transactions.date AT TIME ZONE 'UTC') as year")
            ->selectRaw("EXTRACT(MONTH FROM transactions.date AT TIME ZONE 'UTC') as month")
            ->selectRaw('SUM(CASE WHEN c.is_income = true THEN transactions.price ELSE 0 END) as income')
            ->selectRaw('SUM(CASE WHEN c.is_income = false THEN transactions.price ELSE 0 END) as outcome')
            ->join(
                DB::raw('categories as c'),
                fn (JoinClause $join): JoinClause => $join
                    ->on('c.id', '=', 'transactions.category_id')
                    ->on('c.user_id', '=', 'transactions.user_id')
                    ->whereNull('c.deleted_at')
            )
            ->where('c.user_id', $expensesData->userId)
            ->where('transactions.user_id', $expensesData->userId)
            ->where('transactions.date', '>=', $startDate)
            ->groupByRaw('1, 2')
            ->orderByRaw('1, 2')
            ->get()
            ->map(function (Transaction $transaction) use ($withDifferentYears): ExpenseResource {
                $year = $transaction->getAttribute('year');
                $month = $transaction->getAttribute('month');

                return ExpenseResource::from([
                    'month' => sprintf(
                        "%s%s",
                        Carbon::createFromFormat('Y-m', "$year-$month")->format('M'),
                        $withDifferentYears ? " $year" : '',
                    ),
                    'income' => $transaction->getAttribute('income'),
                    'outcome' => $transaction->getAttribute('outcome'),
                ]);
            });
    }
}

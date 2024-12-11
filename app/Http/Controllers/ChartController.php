<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Charts\ExpensesDTO;
use App\Models\Transaction;
use App\ViewModels\Charts\ExpensesViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class ChartController extends Controller
{
    public function expenses(Request $request, ExpensesViewModel $expensesViewModel): Collection
    {
        Gate::authorize('viewAny', Transaction::class);

        $data = ExpensesDTO::from([
            ...$request->all(),
            'user_id' => auth()->id(),
        ]);

        return $expensesViewModel->get($data);
    }
}

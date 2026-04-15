<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::id());

        $expenses_per_type = $expenses
            ->selectRaw('type, SUM(total) as total_amount, COUNT(*) as total_count')
            ->groupBy('type')
            ->get();

        # dd($expenses_per_type);

        return view('reports.index', [
            'expenses' => $expenses_per_type,
        ]);
    }
}

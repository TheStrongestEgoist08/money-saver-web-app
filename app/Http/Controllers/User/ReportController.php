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
        return view('reports.index');
    }

public function filter(Request $request)
{
    $baseQuery = Expense::where('user_id', Auth::id());

    // Apply Filters
    if ($request->filled('date_from')) {
        $baseQuery->whereDate('created_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $baseQuery->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('types')) {
        $types = is_array($request->types) ? $request->types : [$request->types];
        $baseQuery->whereIn('type', $types);
    }

    // Table Data
    $expenses = $baseQuery->clone()
                          ->select('id', 'expense_name', 'type', 'total', 'description', 'created_at')
                          ->latest('created_at')
                          ->get();

    // Chart Summary (Separate Query - This fixes the GROUP BY error)
    $summary = $baseQuery->clone()
                         ->selectRaw('type, SUM(total) as total_amount, COUNT(*) as count')
                         ->groupBy('type')
                         ->orderByDesc('total_amount')
                         ->get();

    return response()->json([
        'expenses' => $expenses,
        'summary'  => $summary,
    ]);
}
}

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
        $query = Expense::where('user_id', Auth::id());

        // === Date Filter ===
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // === Category/Type Filter ===
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // === Main Table Data ===
        $expenses = $query->clone()
            ->select('id', 'expense_name', 'type', 'total', 'description', 'created_at')
            ->latest('created_at')
            ->get();

        // === Bar Chart Summary (by Category) ===
        $summary = $query->clone()
            ->selectRaw('type, SUM(total) as total_amount, COUNT(*) as count')
            ->groupBy('type')
            ->orderByDesc('total_amount')
            ->get();

        // === Line Chart Data (Daily Spending Trend) ===
        $daily = $query->clone()
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'expenses' => $expenses,
            'summary'  => $summary,
            'daily'    => $daily,
        ]);
    }
}

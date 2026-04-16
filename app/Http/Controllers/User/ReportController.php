<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function exportPDF(Request $request)
    {
        $type = $request->input('type');
        $dateRange = $request->input('date_range');

        $query = auth()->user()->expenses();

        if ($type) {
            $query->where('type', $type);
        }

        if ($dateRange) {
            $dates = explode(' to ', $dateRange);
            if (count($dates) === 2) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } elseif (count($dates) === 1) {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        $expenses = $query->orderBy('created_at', 'desc')->get();

        // Summary
        $totalSpent = $expenses->sum('total');
        $transactionCount = $expenses->count();
        $average = $transactionCount ? $totalSpent / $transactionCount : 0;

        // Category Summary
        $categorySummary = $expenses->groupBy('type')
            ->map(fn($group, $key) => [
                'type' => $key,
                'total_amount' => $group->sum('total'),
                'count' => $group->count()
            ])
            ->values()
            ->sortByDesc('total_amount');

        // Daily Trend
        $dailyTrend = $expenses->groupBy(fn($exp) => $exp->created_at->format('Y-m-d'))
            ->map(fn($day) => [
                'date' => $day->first()->created_at->format('M d, Y'),
                'total' => $day->sum('total')
            ])
            ->values();

        // Get chart images from frontend
        $barChartImage = $request->get('bar_chart');
        $lineChartImage = $request->get('line_chart');

        $pdf = Pdf::loadView('reports.pdf', compact(
            'expenses',
            'totalSpent',
            'transactionCount',
            'average',
            'categorySummary',
            'dailyTrend',
            'barChartImage',
            'lineChartImage'
        ));

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        $filename = 'Formal_Expense_Report_' . now()->format('Y-m-d_His') . '.pdf';

        return $pdf->download($filename);
    }
}

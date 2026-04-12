<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Current Balance
        $userBalance = $user->balance ?? 0;

        // Total Expenses This Month
        $totalExpensesThisMonth = Expense::where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Total Transactions (All time)
        $totalTransactions = Expense::where('user_id', $user->id)->count();

        // Recent Expenses (Latest 5)
        $recentExpenses = Expense::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Category Breakdown for Chart
        $categoryData = Expense::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->selectRaw('type, SUM(total) as total_amount')
            ->groupBy('type')
            ->orderBy('total_amount', 'desc')
            ->get();

        $categoryLabels = $categoryData->pluck('type');
        $categoryAmounts = $categoryData->pluck('total_amount');

        // 🔥 Top Expense (Most Expensive Category This Month)
        $topExpense = $categoryData->first(); // Get the highest one

        $topExpenseCategory = $topExpense ? $topExpense->type : 'No expenses yet';
        $topExpenseAmount   = $topExpense ? $topExpense->total_amount : 0;

        return view('dashboard', compact(
            'userBalance',
            'totalExpensesThisMonth',
            'totalTransactions',
            'recentExpenses',
            'categoryLabels',
            'categoryAmounts',
            'topExpenseCategory',
            'topExpenseAmount'
        ));
    }
}

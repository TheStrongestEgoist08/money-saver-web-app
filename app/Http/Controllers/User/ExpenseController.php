<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

use App\Models\Expense;
use App\Models\Wallet;

class ExpenseController extends Controller
{
    public function index()
    {
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        $wallets = Wallet::where('user_id', auth()->id())
            ->where('balance', '>', 0)
            ->orderBy('wallet_name')
            ->get();

        $expenses = Expense::where('user_id', auth()->id())
            ->latest()
            ->paginate(30);

        $categoryData = Expense::where('user_id', auth()->id())
            ->selectRaw('type, SUM(total) as total_amount')
            ->groupBy('type')
            ->orderBy('total_amount', 'desc')
            ->get();

        $categoryLabels = $categoryData->pluck('type');
        $categoryAmounts = $categoryData->pluck('total_amount');

        $totalExpense = Expense::where('user_id', auth()->id())
            ->sum('total');

        return view('expenses.index', [
            'expenses' => $expenses,
            'categoryLabels' => $categoryLabels,
            'categoryAmounts' => $categoryAmounts,
            'totalExpense' => $totalExpense,
            'wallets' => $wallets,
        ]);
    }

    public function addExpenses(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validatedData = $request->validate([
            'wallet_id' => ['required', 'integer', 'exists:wallets,id'],
            'expenses' => ['required', 'array', 'min:1'],
            'expenses.*.expense_name' => ['required', 'string', 'max:50'],
            'expenses.*.type' => ['required', 'string'],
            'expenses.*.quantity' => ['nullable', 'integer', 'min:1'],
            'expenses.*.price' => ['required', 'numeric', 'min:0.01'],
            'expenses.*.description' => ['nullable', 'string', 'max:500'],
        ]);

        $wallet = Wallet::where('id', $validatedData['wallet_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $totalAllExpenses = 0;
        $expensesToCreate = [];

        foreach ($validatedData['expenses'] as $expense) {
            $quantity = $expense['quantity'] ?? 1;
            $price    = $expense['price'];
            $total    = $quantity * $price;

            $totalAllExpenses += $total;

            $expensesToCreate[] = [
                'user_id'      => $user->id,
                'expense_name' => $expense['expense_name'],
                'type'         => $expense['type'],
                'quantity'     => $quantity,
                'price'        => $price,
                'total'        => $total,
                'description'  => $expense['description'] ?? null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        if ($user->balance < $totalAllExpenses || $wallet->balance < $totalAllExpenses) {
            return Redirect::back()
                ->with('error', 'Insufficient Balance');
        }

        try {
            DB::beginTransaction();

            Expense::insert($expensesToCreate);

            $wallet->decrement('balance', $totalAllExpenses);
            $user->decrement('balance', $totalAllExpenses);

            DB::commit();

            return Redirect::back()
                ->with('success', count($expensesToCreate) . ' Expense(s) Added Successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return Redirect::back()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }
}

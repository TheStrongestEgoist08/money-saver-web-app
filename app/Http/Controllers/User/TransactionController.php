<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        # dd($transactions);

        return view('transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function filter (Request $request)
    {
        $validated_data = $request->validate([
            'type' => [
                'nullable',
                'string',
                'in:Expense,Transfer,Wallet Added,Wallet Deleted,Balance Added'
            ]
        ]);

        $transactions = Transaction::where('user_id', Auth::id())
            ->when($request->filled('type'), function ($query) use ($validated_data) {
                $query->where('type', $validated_data['type']);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
        ]);
    }
}

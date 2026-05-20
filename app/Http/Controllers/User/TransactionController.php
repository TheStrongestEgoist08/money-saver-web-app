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
            ->paginate(10);

        # dd($transactions);

        return view('transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function filter (Request $request)
    {

    }
}

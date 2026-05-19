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

        $transactions->setCollection(
            $transactions->getCollection()->groupBy([
                'type',
                fn ($item) => $item->created_at->format('Y-m-d')
            ])
        );

        # dd($transactions);

        return view('transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function filter (Request $request) {

    }
}

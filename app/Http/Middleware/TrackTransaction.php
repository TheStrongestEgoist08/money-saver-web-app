<?php

namespace App\Http\Middleware;

use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Wallet;

class TrackTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful() || $response->isRedirection()) {
            $this->logTransaction($request);
        }

        return $response;
    }

    /**
     * Log the transaction based on the route and request data.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function logTransaction(Request $request)
    {
        $routeName = $request->route()->getName();
        $user = $request->user();

        $data = [
            'user_id' => $user?->id,
            'wallet_id' => null,
            'type' => 'activity',
            'amount' => 0,
            'description' => '',
            'metadata' => [],
        ];

        // Customize based on route name
        match ($routeName) {
            'user.expenses.add' => $this->logExpense($data, $request),
            'user.wallet.newWallet' => $this->logNewWallet($data, $request),
            'user.wallets.add-balance' => $this->logAddBalance($data, $request),
            'user.wallets.transfer' => $this->logTransfer($data, $request),
            'user.wallets.destroy' => $this->logDeleteWallet($data, $request),
            default => null,
        };

        if (!empty($data['description'])) {
            Transaction::create($data);
        }
    }

    /**
     * Log expenses based on the request data.
     *
     * @param array $data
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function logExpense(&$data, Request $request)
    {
        $expenses = $request->input('expenses', []);

        if (empty($expenses) || !is_array($expenses)) {
            return;
        }

        $totalAmount = 0;
        $expenseDetails = [];

        foreach ($expenses as $expense) {
            $quantity = $expense['quantity'] ?? 1;
            $price    = $expense['price'] ?? 0;
            $total    = $quantity * $price;

            $totalAmount += $total;

            $expenseDetails[] = [
                'expense_name' => $expense['expense_name'] ?? null,
                'type'         => $expense['type'] ?? null,
                'quantity'     => $quantity,
                'price'        => $price,
                'total'        => $total,
                'description'  => $expense['description'] ?? null,
            ];
        }

        // Prepare main transaction data
        $transactionData = $data;

        $transactionData['type']        = 'Expense';
        $transactionData['amount']      = $totalAmount;
        $transactionData['wallet_id']   = $request->input('wallet_id');
        $transactionData['description'] = 'No. of Expenses (' . count($expenses) . ')';

        // Store all expenses in metadata
        $transactionData['metadata'] = [
            'expenses'      => $expenseDetails,
            'total_expenses'=> count($expenses),
            'total_amount'  => $totalAmount,
        ];

        // Create only ONE transaction
        Transaction::create($transactionData);
    }

    /**
     * Log balance addition to a wallet.
     *
     * @param array $data
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function logAddBalance(&$data, Request $request)
    {
        $data['type'] = 'Balance Added';
        $data['amount'] = abs($request->input('amount', 0));
        $data['wallet_id'] = $request->input('wallet_id');
        $data['description'] = 'Balance added to wallet';
    }

    /**
     * Log a transfer between wallets.
     *
     * @param array $data
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function logTransfer(&$data, Request $request)
    {
        $fromWalletId = $request->input('from_wallet_id');
        $toWalletId = $request->input('to_wallet_id');

        $fromWallet = Wallet::findOrFail($fromWalletId);
        $toWallet = Wallet::findOrFail($toWalletId);

        $data['wallet_id'] = $fromWalletId;
        $data['type'] = 'Transfer';
        $data['amount'] = abs($request->input('amount', 0));
        $data['description'] = 'Transfer between wallets';
        $data['metadata'] = [
            'from_wallet' => $fromWallet->wallet_name,
            'to_wallet'   => $toWallet->wallet_name,
        ];
    }

    /**
     * Log the creation of a new wallet.
     *
     * @param array $data
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function logNewWallet(&$data, Request $request)
    {
        $data['type'] = 'Wallet Created';
        $data['description'] = 'New wallet created';
    }

    /**
     * Log the deletion of a wallet.
     *
     * @param array $data
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    private function logDeleteWallet(&$data, Request $request)
    {
        $data['type'] = 'Wallet Deleted';
        $data['description'] = 'Wallet deleted';
    }
}

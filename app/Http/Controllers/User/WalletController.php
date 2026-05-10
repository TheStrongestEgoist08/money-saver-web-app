<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $wallets = $user->wallets()->latest()->get();

        return view('balance.index', compact('wallets'));
    }

    /**
     * Store New Wallet
     */
    public function newWallet(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validatedData = $request->validate([
            'wallet_name' => ['nullable', 'string', 'max:100'],
            'wallet_type' => ['required', 'in:bank,e-wallet,wallet'],
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            Wallet::create([
                'user_id'      => $user->id,
                'wallet_name'  => $validatedData['wallet_name'],
                'wallet_type'  => $validatedData['wallet_type'],
                'balance'      => 0.00,
            ]);

            DB::commit();

            return redirect()
                ->route('user.wallets')
                ->with('success', 'Wallet created successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->route('user.wallets')
                ->with('error', 'Failed to create wallet. Please try again.');
        }
    }

    /**
     * Add Balance to a Specific Wallet
     */
    public function addBalance(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validatedData = $request->validate([
            'wallet_id' => ['required', 'exists:wallets,id'],
            'amount'    => ['required', 'numeric', 'min:1'],
        ]);

        try {
            DB::beginTransaction();

            $wallet = Wallet::where('id', $validatedData['wallet_id'])
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

            $wallet->balance += $validatedData['amount'];
            $wallet->save();

            DB::commit();

            return redirect()->route('user.wallets')
                ->with('success', 'Balance added successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('user.wallets')
                ->with('error', 'Failed to add balance. Please try again.');
        }
    }

    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'from_wallet_id' => ['required', 'exists:wallets,id'],
            'to_wallet_id'   => ['required', 'exists:wallets,id', 'different:from_wallet_id'],
            'amount'         => ['required', 'numeric', 'min:0.01'],
        ]);

        try {
            DB::beginTransaction();

            $fromWallet = Wallet::where('id', $validated['from_wallet_id'])
                                ->where('user_id', Auth::id())
                                ->firstOrFail();

            $toWallet = Wallet::where('id', $validated['to_wallet_id'])
                              ->where('user_id', Auth::id())
                              ->firstOrFail();

            if ($fromWallet->balance < $validated['amount'] || $fromWallet->balance == 0) {
                return redirect()->route('user.wallets')
                    ->with('error', 'Insufficient balance in source wallet.');
            }

            $fromWallet->decrement('balance', $validated['amount']);
            $toWallet->increment('balance', $validated['amount']);

            DB::commit();

            return redirect()
                ->route('user.wallets')
                ->with('success', 'Transfer completed successfully!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->route('user.wallets')
                ->with('error', 'Transfer failed. Please try again.');
        }
    }

    public function destroy(Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) {
            abort(403);
        }

        // Optional: Prevent deletion if balance exists
        if ($wallet->balance > 0) {
            return redirect()->route('user.wallets')
                ->with('error', 'Cannot delete wallet with remaining balance. Please withdraw first.');
        }

        try {
            $wallet->delete();

            return redirect()->route('user.wallets')
                ->with('success', 'Wallet deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user.wallets')
                ->with('error', 'Failed to delete wallet.');
        }
    }
}

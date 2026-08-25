<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 4;

        $types = [
            'Balance Added',
            'Expense',
            'Transfer',
            'Wallet Created',
            'Wallet Deleted',
        ];

        $data = [];
        $now = Carbon::now();

        // Try to get wallets belonging to user 4
        $wallets = class_exists(Wallet::class)
            ? Wallet::where('user_id', $userId)->pluck('id')->toArray()
            : [];

        // ~150 transactions across different months
        for ($i = 0; $i < 150; $i++) {
            $type = $types[array_rand($types)];
            $amount = round(mt_rand(100, 15000) / 10, 2);

            $walletId = !empty($wallets) ? $wallets[array_rand($wallets)] : null;

            $monthsAgo = rand(0, 7);
            $createdAt = $now->copy()
                ->subMonths($monthsAgo)
                ->subDays(rand(0, 27))
                ->setTime(rand(7, 23), rand(0, 59));

            $description = match ($type) {
                'Balance Added'   => 'Top-up via bank transfer / GCash',
                'Expense'         => 'Payment for daily expense',
                'Transfer'        => 'Transfer between wallets',
                'Wallet Created'  => 'New wallet created',
                'Wallet Deleted'  => 'Wallet permanently deleted',
                default           => null,
            };

            $metadata = match ($type) {
                'Expense'  => json_encode(['category' => 'General', 'source' => 'app']),
                'Transfer' => json_encode(['from' => 'Main', 'to' => 'Savings']),
                default    => null,
            };

            $data[] = [
                'user_id'     => $userId,
                'wallet_id'   => $walletId,
                'type'        => $type,
                'amount'      => $amount,
                'description' => $description,
                'metadata'    => $metadata,
                'created_at'  => $createdAt,
                'updated_at'  => $createdAt,
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('transactions')->insert($chunk);
        }
    }
}

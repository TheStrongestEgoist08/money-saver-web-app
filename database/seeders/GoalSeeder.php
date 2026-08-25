<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 4;

        $goalNames = [
            'Emergency Fund',
            'New Laptop',
            'Vacation to Japan',
            'Downpayment for Condo',
            'Wedding Fund',
            'Car Purchase',
            'Home Renovation',
            'Education Fund',
            'New Phone',
            'Investment Portfolio',
            'Christmas Savings',
            'Health Insurance',
            'Business Capital',
            'Travel to Europe',
            'Furniture Upgrade',
        ];

        $data = [];
        $now = Carbon::now();

        // ~40 goals, all set to completed
        for ($i = 0; $i < 40; $i++) {
            $name = $goalNames[array_rand($goalNames)];

            $targetAmount = round(mt_rand(5000, 250000) / 10, 2);
            $savedAmount  = round($targetAmount * (rand(70, 100) / 100), 2);

            $monthsAgo = rand(1, 10);
            $createdAt = $now->copy()
                ->subMonths($monthsAgo)
                ->subDays(rand(0, 20));

            $targetDate = $createdAt->copy()
                ->addMonths(rand(2, 8))
                ->addDays(rand(0, 15));

            $data[] = [
                'user_id'       => $userId,
                'goal_name'     => $name,
                'target_amount' => $targetAmount,
                'saved_amount'  => $savedAmount,
                'target_date'   => $targetDate->toDateString(),
                'status'        => 'completed',
                'description'   => "Saving for {$name}",
                'image'         => null,
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt->copy()->addDays(rand(5, 40)),
            ];
        }

        foreach (array_chunk($data, 20) as $chunk) {
            DB::table('goals')->insert($chunk);
        }
    }
}

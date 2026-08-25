<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 4;

        $types = [
            'Food', 'Groceries', 'Transportation', 'Bills', 'Utilities',
            'Personal Care', 'Household', 'Health', 'Clothing',
            'Entertainment', 'Education', 'Savings', 'Gifts',
            'Maintenance', 'Subscriptions', 'Others'
        ];

        $expenseNames = [
            'Food' => ['Lunch at cafe', 'Dinner takeout', 'Breakfast sandwich', 'Coffee & pastry', 'Street food'],
            'Groceries' => ['Weekly groceries', 'Fresh produce', 'Meat & poultry', 'Dairy products', 'Snacks & drinks'],
            'Transportation' => ['Grab ride', 'Fuel refill', 'Parking fee', 'Bus fare', 'Train ticket'],
            'Bills' => ['Electricity bill', 'Water bill', 'Internet bill', 'Phone bill', 'Condo dues'],
            'Utilities' => ['Gas bill', 'Trash collection', 'Home internet', 'Mobile data'],
            'Personal Care' => ['Haircut', 'Skincare products', 'Toiletries', 'Gym membership'],
            'Household' => ['Cleaning supplies', 'Laundry detergent', 'Kitchen items', 'Home decor'],
            'Health' => ['Medicine', 'Doctor consultation', 'Vitamins', 'Dental checkup'],
            'Clothing' => ['T-shirt', 'Jeans', 'Shoes', 'Jacket', 'Accessories'],
            'Entertainment' => ['Movie tickets', 'Streaming subscription', 'Concert ticket', 'Game purchase'],
            'Education' => ['Online course', 'Books', 'Workshop fee', 'School supplies'],
            'Savings' => ['Emergency fund', 'Investment contribution'],
            'Gifts' => ['Birthday gift', 'Anniversary present', 'Holiday gift'],
            'Maintenance' => ['Car service', 'Appliance repair', 'Plumbing fix'],
            'Subscriptions' => ['Netflix', 'Spotify', 'Adobe CC', 'Cloud storage'],
            'Others' => ['Miscellaneous', 'Unexpected expense', 'Donation'],
        ];

        $data = [];
        $now = Carbon::now();

        // ~120 expenses across different months
        for ($i = 0; $i < 120; $i++) {
            $type = $types[array_rand($types)];
            $name = $expenseNames[$type][array_rand($expenseNames[$type])];

            $quantity = rand(0, 1) ? rand(1, 5) : null;
            $price = round(mt_rand(50, 3500) / 10, 2);
            $total = $quantity ? round($price * $quantity, 2) : $price;

            $monthsAgo = rand(0, 7);
            $createdAt = $now->copy()
                ->subMonths($monthsAgo)
                ->subDays(rand(0, 27))
                ->setTime(rand(8, 22), rand(0, 59));

            $data[] = [
                'user_id'      => $userId,
                'expense_name' => $name,
                'type'         => $type,
                'quantity'     => $quantity,
                'price'        => $price,
                'total'        => $total,
                'description'  => rand(0, 1) ? "Paid via cash/app for {$name}" : null,
                'created_at'   => $createdAt,
                'updated_at'   => $createdAt,
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            DB::table('expenses')->insert($chunk);
        }
    }
}

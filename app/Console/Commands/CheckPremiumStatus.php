<?php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckPremiumStatus extends Command
{
    protected $signature = 'premium:check';

    protected $description = 'Check expired premium subscriptions';

    public function handle()
    {
        $expiredUsers = User::where('is_premium', true)
            ->whereNotNull('premium_until')
            ->where('premium_until', '<', Carbon::now())
            ->get();

        foreach ($expiredUsers as $user) {
            $user->update([
                'is_premium' => false,
                'premium_until' => null,
            ]);

            $this->info("Premium expired for user ID: {$user->id}");
        }

        $this->info('Premium status check completed.');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Goal;
use Illuminate\Console\Command;

class CheckOverdueGoals extends Command
{
    protected $signature = 'goals:check-overdue';
    protected $description = 'Mark active goals as failed if target date has passed';

    public function handle()
    {
        $updated = Goal::where('status', 'active')
            ->whereNotNull('target_date')
            ->whereDate('target_date', '<', now()->toDateString())
            ->update([
                'status' => 'failed'
            ]);

        $this->info("✅ {$updated} overdue goal(s) marked as failed.");

        return 0;
    }
}

<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Facades\Prism;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AISuggestionService
{
    public function getSuggestions(array $data)
    {
        $user = Auth::user();
        $conversationId = $data['conversation_id'] ?? null;
        $userMessage = trim($data['message'] ?? '');
        $requestedPeriod = $data['period'] ?? 'month';

        if (empty($userMessage)) {
            return ['success' => false, 'message' => 'Message is required.'];
        }

        $periodInfo = $this->detectPeriodFromMessage($userMessage, $requestedPeriod);
        $dateRanges = $this->getDateRanges($periodInfo);

        $periodSummaries = [];

        foreach ($dateRanges as $key => $range) {
            $expenses = Expense::where('user_id', $user->id)
                ->where('created_at', '>=', $range['start'])
                ->where('created_at', '<=', $range['end'])
                ->orderBy('created_at', 'desc')           // Most recent first
                ->get();

            // === Summary Data ===
            $total = $expenses->sum('total') ?? 0;

            $summary = $expenses->groupBy('type')
                ->map(fn($group) => round($group->sum('total'), 2))
                ->toArray();

            // === Detailed Expenses (New) ===
            $details = $expenses->map(function ($expense) {
                return [
                    'id'          => $expense->id,
                    'name'        => $expense->expense_name ?? $expense->description ?? 'Untitled Expense',
                    'amount'      => round($expense->total, 2),
                    'type'        => $expense->type,
                    'date'        => $expense->created_at->format('M d, Y'),
                    'time'        => $expense->created_at->format('h:i A'),
                ];
            })->toArray();

            $periodSummaries[$key] = [
                'period'  => $range['label'],
                'total'   => $total,
                'summary' => $summary,
                'details' => $details,
                'start'   => $range['start'],
                'end'     => $range['end'],
                'count'   => $expenses->count()
            ];
        }

        // Get or create conversation
        $conversation = $conversationId
            ? Conversation::where('user_id', $user->id)->find($conversationId)
            : null;

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'title'   => $this->generateConversationTitle($userMessage, $periodInfo),
                'period'  => $periodInfo['main_period'],
                'messages' => []
            ]);
        }

        $messages = $conversation->messages ?? [];

        // Build Prompt (Now includes details)
        $prompt = "You are Luna, a friendly, warm, and intelligent personal finance advisor.\n\n";
        $prompt .= "User: {$user->name}\n";
        $prompt .= "Current Date: " . now()->format('F d, Y') . "\n\n";

        $prompt .= "Here is the financial data I analyzed:\n\n";

        foreach ($periodSummaries as $info) {
            $prompt .= "📅 {$info['period']} ({$info['start']->format('M d, Y')} - {$info['end']->format('M d, Y')})\n";
            $prompt .= "Total Spent: ₱" . number_format($info['total'], 2) . "\n";
            $prompt .= "Number of transactions: {$info['count']}\n";
            $prompt .= "By Category: " . json_encode($info['summary'], JSON_PRETTY_PRINT) . "\n\n";

            // === Add Expense Details ===
            if (!empty($info['details'])) {
                $prompt .= "📋 Individual Expenses:\n";
                foreach ($info['details'] as $expense) {
                    $prompt .= "- {$expense['date']} {$expense['time']} | {$expense['name']} | ₱" .
                              number_format($expense['amount'], 2) . " | {$expense['type']}\n";
                }
                $prompt .= "\n";
            }
        }

        $prompt .= "Rules:\n";
        $prompt .= "- Always base your answers strictly on the data provided above.\n";
        $prompt .= "- You can now refer to specific expenses by name and date.\n";
        $prompt .= "- Be honest if data is partial or for a single day.\n";
        $prompt .= "- Be conversational, warm, and helpful.\n\n";

        $prompt .= "Previous conversation:\n";
        foreach ($messages as $msg) {
            $role = $msg['role'] === 'user' ? 'User' : 'Luna';
            $prompt .= "{$role}: {$msg['content']}\n\n";
        }

        $prompt .= "User: {$userMessage}\n\n";
        $prompt .= "Respond naturally as Luna.";

        try {
            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withMaxTokens(4096)
                ->usingTemperature(0.7)
                ->generate();

            $aiReply = $response->text ?? "Hey! I'd love to help you with your expenses.";

            $messages[] = ['role' => 'user', 'content' => $userMessage];
            $messages[] = ['role' => 'assistant', 'content' => $aiReply];

            $conversation->update(['messages' => $messages]);

            return [
                'success'        => true,
                'response'       => $aiReply,
                'conversation_id'=> $conversation->id,
                'period'         => $periodInfo['main_period']
            ];

        } catch (\Exception $e) {
            Log::error('AI Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Sorry, I had trouble responding.'];
        }
    }

    // Other methods remain the same
    private function generateConversationTitle(string $message, array $periodInfo): string
    {
        $message = strtolower($message);

        if ($periodInfo['main_period'] === 'specific_day') {
            return $periodInfo['label'] . ' Spending';
        }
        if (str_contains($message, 'this month') || $periodInfo['main_period'] === 'month') {
            return 'This Month Overview';
        }
        if (str_contains($message, 'last month')) {
            return 'Last Month Summary';
        }
        if (isset($periodInfo['month'])) {
            $monthName = Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->format('F');
            return "{$monthName} {$periodInfo['year']} Report";
        }

        return ucfirst(substr(trim($message), 0, 45)) . '...';
    }

    private function detectPeriodFromMessage(string $message, string $defaultPeriod): array
    {
        $message = strtolower(trim($message));
        $now = now();

        if (str_contains($message, 'today')) {
            return [
                'main_period' => 'specific_day',
                'start' => $now->copy()->startOfDay(),
                'end'   => $now->copy()->endOfDay(),
                'label' => 'Today'
            ];
        }

        if (str_contains($message, 'yesterday')) {
            return [
                'main_period' => 'specific_day',
                'start' => $now->copy()->subDay()->startOfDay(),
                'end'   => $now->copy()->subDay()->endOfDay(),
                'label' => 'Yesterday'
            ];
        }

        if (preg_match('/(\w+)\s+(\d{1,2})/', $message, $matches)) {
            $monthName = $matches[1];
            $day = (int)$matches[2];

            $months = ['january'=>1,'jan'=>1,'february'=>2,'feb'=>2,'march'=>3,'mar'=>3,'april'=>4,'apr'=>4,
                       'may'=>5,'june'=>6,'jun'=>6,'july'=>7,'jul'=>7,'august'=>8,'aug'=>8,
                       'september'=>9,'sep'=>9,'october'=>10,'oct'=>10,'november'=>11,'nov'=>11,
                       'december'=>12,'dec'=>12];

            if (isset($months[$monthName])) {
                $year = $now->year;
                $date = Carbon::create($year, $months[$monthName], $day);
                return [
                    'main_period' => 'specific_day',
                    'start' => $date->copy()->startOfDay(),
                    'end'   => $date->copy()->endOfDay(),
                    'label' => $date->format('F d, Y')
                ];
            }
        }

        $months = ['january'=>1,'jan'=>1,'february'=>2,'feb'=>2,'march'=>3,'mar'=>3,'april'=>4,'apr'=>4,
                   'may'=>5,'june'=>6,'jun'=>6,'july'=>7,'jul'=>7,'august'=>8,'aug'=>8,
                   'september'=>9,'sep'=>9,'october'=>10,'oct'=>10,'november'=>11,'nov'=>11,
                   'december'=>12,'dec'=>12];

        foreach ($months as $name => $num) {
            if (str_contains($message, $name)) {
                $year = $now->year;
                if (str_contains($message, 'last') && $now->month < $num) $year--;
                return ['main_period' => 'specific_month', 'month' => $num, 'year' => $year];
            }
        }

        if (str_contains($message, 'last month') || str_contains($message, 'previous month')) {
            return ['main_period' => 'last_month'];
        }
        if (str_contains($message, 'this month') || str_contains($message, 'current month')) {
            return ['main_period' => 'month'];
        }
        if (str_contains($message, 'last week')) {
            return ['main_period' => 'last_week'];
        }
        if (str_contains($message, 'this week')) {
            return ['main_period' => 'week'];
        }

        return ['main_period' => $defaultPeriod];
    }

    private function getDateRanges(array $periodInfo): array
    {
        $now = now()->startOfDay();

        if ($periodInfo['main_period'] === 'specific_day') {
            return [[
                'start' => $periodInfo['start'],
                'end'   => $periodInfo['end'],
                'label' => $periodInfo['label']
            ]];
        }

        return match ($periodInfo['main_period']) {
            'specific_month' => [[
                'start' => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->startOfMonth(),
                'end'   => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->endOfMonth(),
                'label' => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->format('F Y')
            ]],

            'month' => [[
                'start' => $now->copy()->startOfMonth(),
                'end'   => $now->copy()->endOfMonth(),
                'label' => 'This Month'
            ]],

            'last_month' => [[
                'start' => $now->copy()->subMonth()->startOfMonth(),
                'end'   => $now->copy()->subMonth()->endOfMonth(),
                'label' => 'Last Month'
            ]],

            'week' => [[
                'start' => $now->copy()->startOfWeek(),
                'end'   => $now->copy()->endOfWeek(),
                'label' => 'This Week'
            ]],

            'last_week' => [[
                'start' => $now->copy()->subWeek()->startOfWeek(),
                'end'   => $now->copy()->subWeek()->endOfWeek(),
                'label' => 'Last Week'
            ]],

            default => [[
                'start' => $now->copy()->startOfMonth(),
                'end'   => $now->copy()->endOfMonth(),
                'label' => 'This Month'
            ]]
        };
    }
}

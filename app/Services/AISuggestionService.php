<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Prism\Prism\Facades\Prism;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AISuggestionService
{
    private const MAX_DETAILED_EXPENSES = 25; // Prevent token explosion

    public function getSuggestions(array $data)
    {
        $user = Auth::user();
        $conversationId = $data['conversation_id'] ?? null;
        $userMessage = trim($data['message'] ?? '');
        $requestedPeriod = $data['period'] ?? 'month';

        if (empty($userMessage)) {
            return ['success' => false, 'message' => 'Message is required.'];
        }

        // Detect period and fetch data
        $periodInfo = $this->detectPeriodFromMessage($userMessage, $requestedPeriod);
        $dateRanges = $this->getDateRanges($periodInfo);

        $periodSummaries = [];

        foreach ($dateRanges as $key => $range) {
            $expenses = Expense::where('user_id', $user->id)
                ->where('created_at', '>=', $range['start'])
                ->where('created_at', '<=', $range['end'])
                ->orderBy('created_at', 'desc')
                ->get();

            $total = $expenses->sum('total') ?? 0;

            $summaryByType = $expenses->groupBy('type')
                ->map(fn($group) => round($group->sum('total'), 2))
                ->toArray();

            // Limit details sent to AI
            $details = $expenses->take(self::MAX_DETAILED_EXPENSES)->map(function ($expense) {
                return [
                    'id'     => $expense->id,
                    'name'   => $expense->expense_name ?? $expense->description ?? 'Untitled Expense',
                    'amount' => round((float) $expense->total, 2),
                    'type'   => $expense->type,
                    'date'   => $expense->created_at->format('M d, Y'),
                    'time'   => $expense->created_at->format('h:i A'),
                ];
            })->toArray();

            $periodSummaries[$key] = [
                'period'  => $range['label'],
                'total'   => $total,
                'summary' => $summaryByType,
                'details' => $details,
                'start'   => $range['start'],
                'end'     => $range['end'],
                'count'   => $expenses->count(),
                'details_limited' => $expenses->count() > self::MAX_DETAILED_EXPENSES,
            ];
        }

        // Get or create conversation
        $conversation = $this->getOrCreateConversation($user, $conversationId, $userMessage, $periodInfo);

        $messages = $conversation->messages ?? [];

        // Build optimized prompt
        $prompt = $this->buildPrompt($user, $periodSummaries, $messages, $userMessage);

        try {
            $response = Prism::text()
                ->using('gemini', 'gemini-2.5-flash')
                ->withPrompt($prompt)
                ->withMaxTokens(4096)
                ->usingTemperature(0.7)
                ->generate();

            $aiReply = $response->text ?? "Hey! I'd love to help you analyze your expenses.";

            // Save conversation
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
            Log::error('AI Suggestion Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'message' => $userMessage
            ]);
            return ['success' => false, 'message' => 'Sorry, I had trouble processing your request.'];
        }
    }

    private function buildPrompt($user, array $periodSummaries, array $messages, string $userMessage): string
    {
        $prompt = "You are Luna, a friendly, warm, and intelligent personal finance advisor.\n\n";
        $prompt .= "User: {$user->name}\n";
        $prompt .= "Current Date: " . now()->format('F d, Y') . "\n\n";

        $prompt .= "Here is the financial data I analyzed:\n\n";

        foreach ($periodSummaries as $info) {
            $prompt .= "📅 {$info['period']} ({$info['start']->format('M d, Y')} - {$info['end']->format('M d, Y')})\n";
            $prompt .= "Total Spent: ₱" . number_format($info['total'], 2) . "\n";
            $prompt .= "Transactions: {$info['count']}\n";

            if (!empty($info['summary'])) {
                $prompt .= "By Category: " . json_encode($info['summary'], JSON_PRETTY_PRINT) . "\n";
            }

            if (!empty($info['details'])) {
                $prompt .= "\n📋 Recent Individual Expenses:\n";
                foreach ($info['details'] as $expense) {
                    $prompt .= "- {$expense['date']} {$expense['time']} | {$expense['name']} | ₱" .
                              number_format($expense['amount'], 2) . " | {$expense['type']}\n";
                }

                if ($info['details_limited'] ?? false) {
                    $prompt .= "(Note: Only the most recent " . self::MAX_DETAILED_EXPENSES . " expenses are shown.)\n";
                }
            }
            $prompt .= "\n";
        }

        $prompt .= "Rules:\n";
        $prompt .= "- Base your answers strictly on the data provided.\n";
        $prompt .= "- Be honest if data is limited or partial.\n";
        $prompt .= "- You may refer to specific expenses by name and date.\n";
        $prompt .= "- Be conversational, warm, and helpful.\n\n";

        if (!empty($messages)) {
            $prompt .= "Previous conversation:\n";
            foreach ($messages as $msg) {
                $role = $msg['role'] === 'user' ? 'User' : 'Luna';
                $prompt .= "{$role}: {$msg['content']}\n\n";
            }
        }

        $prompt .= "User: {$userMessage}\n\n";
        $prompt .= "Respond naturally as Luna.";

        return $prompt;
    }

    private function getOrCreateConversation($user, ?int $conversationId, string $message, array $periodInfo): Conversation
    {
        if ($conversationId) {
            $conversation = Conversation::where('user_id', $user->id)
                ->find($conversationId);

            if ($conversation) {
                return $conversation;
            }
        }

        return Conversation::create([
            'user_id' => $user->id,
            'title'   => $this->generateConversationTitle($message, $periodInfo),
            'period'  => $periodInfo['main_period'],
            'messages' => []
        ]);
    }

    private function generateConversationTitle(string $message, array $periodInfo): string
    {
        $message = strtolower(trim($message));

        if ($periodInfo['main_period'] === 'specific_day' && !empty($periodInfo['label'])) {
            return $periodInfo['label'] . ' Spending';
        }

        if (str_contains($message, 'this month') || $periodInfo['main_period'] === 'month') {
            return 'This Month Overview';
        }

        if (str_contains($message, 'last month')) {
            return 'Last Month Summary';
        }

        return ucfirst(substr($message, 0, 50)) . '...';
    }

    private function detectPeriodFromMessage(string $message, string $defaultPeriod): array
    {
        $message = strtolower(trim($message));
        $now = now();

        // Specific day patterns
        if (str_contains($message, 'today')) {
            return $this->makeDayPeriod('Today', $now);
        }

        if (str_contains($message, 'yesterday')) {
            return $this->makeDayPeriod('Yesterday', $now->copy()->subDay());
        }

        // Month + Day (e.g., "january 15")
        if (preg_match('/(january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sep|october|oct|november|nov|december|dec)\s+(\d{1,2})/i', $message, $matches)) {
            $monthName = strtolower($matches[1]);
            $day = (int)$matches[2];

            $months = ['january'=>1,'jan'=>1,'february'=>2,'feb'=>2,'march'=>3,'mar'=>3,'april'=>4,'apr'=>4,
                       'may'=>5,'june'=>6,'jun'=>6,'july'=>7,'jul'=>7,'august'=>8,'aug'=>8,
                       'september'=>9,'sep'=>9,'october'=>10,'oct'=>10,'november'=>11,'nov'=>11,
                       'december'=>12,'dec'=>12];

            if (isset($months[$monthName])) {
                $date = Carbon::create($now->year, $months[$monthName], $day);
                return $this->makeDayPeriod($date->format('F d, Y'), $date);
            }
        }

        // Specific month
        $months = ['january'=>1,'jan'=>1,'february'=>2,'feb'=>2,'march'=>3,'mar'=>3,'april'=>4,'apr'=>4,
                   'may'=>5,'june'=>6,'jun'=>6,'july'=>7,'jul'=>7,'august'=>8,'aug'=>8,
                   'september'=>9,'sep'=>9,'october'=>10,'oct'=>10,'november'=>11,'nov'=>11,
                   'december'=>12,'dec'=>12];

        foreach ($months as $name => $num) {
            if (str_contains($message, $name)) {
                $year = $now->year;
                if (str_contains($message, 'last') && $now->month < $num) {
                    $year--;
                }
                return [
                    'main_period' => 'specific_month',
                    'month' => $num,
                    'year' => $year,
                    'label' => Carbon::create($year, $num, 1)->format('F Y')
                ];
            }
        }

        // Quick keywords
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

    private function makeDayPeriod(string $label, Carbon $date): array
    {
        return [
            'main_period' => 'specific_day',
            'start' => $date->copy()->startOfDay(),
            'end'   => $date->copy()->endOfDay(),
            'label' => $label
        ];
    }

    private function getDateRanges(array $periodInfo): array
    {
        $now = now()->startOfDay();

        return match ($periodInfo['main_period']) {
            'specific_day' => [[
                'start' => $periodInfo['start'],
                'end'   => $periodInfo['end'],
                'label' => $periodInfo['label']
            ]],

            'specific_month' => [[
                'start' => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->startOfMonth(),
                'end'   => Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->endOfMonth(),
                'label' => $periodInfo['label'] ?? Carbon::create($periodInfo['year'], $periodInfo['month'], 1)->format('F Y')
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

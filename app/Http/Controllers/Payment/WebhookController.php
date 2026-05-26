<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('PAYMONGO WEBHOOK', $payload);

        $eventType = $payload['data']['attributes']['type'] ?? null;

        if ($eventType !== 'checkout_session.payment.paid') {
            return response()->json([
                'message' => 'Event ignored'
            ], 200);
        }

        // Correct resource extraction
        $resource = $payload['data']['attributes']['data'] ?? [];

        $attributes = $resource['attributes'] ?? [];

        $metadata = $attributes['metadata'] ?? [];

        $userId   = $metadata['user_id'] ?? null;
        $duration = $metadata['duration'] ?? '1 month';

        if (!$userId) {
            Log::warning('No user_id found in metadata');
            return response()->json([
                'message' => 'No user_id'
            ], 200);
        }

        $user = User::find($userId);

        if (!$user) {
            Log::warning("User {$userId} not found");
            return response()->json([
                'message' => 'User not found'
            ], 200);
        }

        $premiumUntil = match ($duration) {
            '1 week'   => now()->addWeek(),
            '1 month'  => now()->addMonth(),
            '3 months' => now()->addMonths(3),
            '1 year'   => now()->addYear(),
            default    => now()->addMonth(),
        };

        $user->update([
            'is_premium' => true,
            'premium_until' => $premiumUntil,
        ]);

        Log::info("User {$userId} upgraded successfully.");

        return response()->json([
            'message' => 'Webhook processed'
        ], 200);
    }
}

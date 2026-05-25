<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        $eventType = $payload['data']['attributes']['type'] ?? null;

        if ($eventType === 'checkout_session.payment.paid') {

            $metadata =
                $payload['data']['attributes']['data']['attributes']['metadata'];

            $userId = $metadata['user_id'];

            $user = User::find($userId);

            if ($user) {

                $user->update([
                    'is_premium' => true,
                    'premium_until' => now()->addMonth(),
                ]);
            }
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}

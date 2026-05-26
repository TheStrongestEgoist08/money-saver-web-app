<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PremiumController extends Controller
{
    protected array $plans = [
        'week' => [
            'amount' => 9900,
            'duration' => '1 week',
        ],

        'month' => [
            'amount' => 19900,
            'duration' => '1 month',
        ],

        'quarter' => [
            'amount' => 49900,
            'duration' => '3 months',
        ],

        'year' => [
            'amount' => 149900,
            'duration' => '1 year',
        ],
    ];

    public function index()
    {
        return view('premium.choose');
    }

    public function upgrade(Request $request)
    {
        $user = auth()->user();

        $plan = $request->input('plan');

        if (!isset($this->plans[$plan])) {
            return back()->with('error', 'Invalid plan selected.');
        }

        $selectedPlan = $this->plans[$plan];

        $response = Http::withBasicAuth(
            env('PAYMONGO_SECRET_KEY'),
            ''
        )->post('https://api.paymongo.com/v1/checkout_sessions', [

            'data' => [
                'attributes' => [

                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => $selectedPlan['amount'],
                            'name' => 'Premium Access (' . ucfirst($plan) . ')',
                            'quantity' => 1,
                        ]
                    ],

                    'payment_method_types' => [
                        'gcash',
                        'card',
                        'paymaya',
                    ],

                    'success_url' => url('/user/premium/success') .
                        '?user=' . $user->id .
                        '&plan=' . $plan,

                    'cancel_url' => url('/user/premium/cancel'),
                ]
            ]
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Failed to create payment session.');
        }

        $checkoutUrl = $response['data']['attributes']['checkout_url'] ?? null;

        if (!$checkoutUrl) {
            return back()->with('error', 'Unable to process payment.');
        }

        return redirect($checkoutUrl);
    }

    public function success(Request $request)
    {
        $user = auth()->user();

        $plan = $request->query('plan');

        if (!$plan || !isset($this->plans[$plan])) {
            return redirect()
                ->route('premium.choose')
                ->with('error', 'Invalid premium plan.');
        }

        if ($user->is_premium && $user->premium_until > now()) {

            $premiumUntil = match ($plan) {
                'week'    => $user->premium_until->addWeek(),
                'month'   => $user->premium_until->addMonth(),
                'quarter' => $user->premium_until->addMonths(3),
                'year'    => $user->premium_until->addYear(),
                default   => $user->premium_until->addMonth(),
            };

        } else {

            $premiumUntil = match ($plan) {
                'week'    => now()->addWeek(),
                'month'   => now()->addMonth(),
                'quarter' => now()->addMonths(3),
                'year'    => now()->addYear(),
                default   => now()->addMonth(),
            };
        }

        $user->update([
            'is_premium'    => true,
            'premium_until' => $premiumUntil,
        ]);

        return view('premium.success');
    }

    public function cancel()
    {
        return view('premium.cancel');
    }
}

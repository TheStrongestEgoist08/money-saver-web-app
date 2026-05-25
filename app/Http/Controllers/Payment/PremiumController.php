<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PremiumController extends Controller
{
    public function upgrade(Request $request)
    {
        $user = auth()->user();

        $response = Http::withBasicAuth(
            env('PAYMONGO_SECRET'),
            ''
        )->post('https://api.paymongo.com/v1/checkout_sessions', [
            
            'data' => [
                'attributes' => [

                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => 19900,
                            'name' => 'Premium Access',
                            'quantity' => 1,
                        ]
                    ],

                    'payment_method_types' => [
                        'gcash',
                        'card',
                        'paymaya',
                    ],

                    'success_url' => url('/premium/success'),
                    'cancel_url' => url('/premium/cancel'),

                    'metadata' => [
                        'user_id' => $user->id
                    ]
                ]
            ]
        ]);

        $checkoutUrl = $response['data']['attributes']['checkout_url'];

        return redirect($checkoutUrl);
    }
}

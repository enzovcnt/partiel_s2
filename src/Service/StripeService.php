<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeService
{
    private string $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }
    public function createCheckoutSession(
        float $amount,
        int $quantity,
        string $productName,
        string $successUrl,
        string $cancelUrl
    ): StripeSession
    {
        Stripe::setApiKey($this->secretKey);

        return StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => $productName],
                    'unit_amount' => (int) ($amount * 100),
                ],
                'quantity' => $quantity,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }

    public function retrieveSession(string $sessionId): StripeSession
    {
        return StripeSession::retrieve($sessionId);
    }
}

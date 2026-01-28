<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    public static function client()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');

        // Force environment based on .env
        if (env('PAYPAL_MODE') === 'production') {
            $environment = new ProductionEnvironment(
                $clientId,
                $clientSecret,
                'https://api.paypal.com'
            );
        } else {
            $environment = new SandboxEnvironment(
                $clientId,
                $clientSecret,
                'https://api.sandbox.paypal.com'
            );
        }

        return new PayPalHttpClient($environment);
    }
}

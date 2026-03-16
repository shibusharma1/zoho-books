<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookReceiverController extends Controller
{

    /**
     * Receive webhook from external application
     */
    public function receive(Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | Step 1: Get JSON payload
        |--------------------------------------------------------------------------
        */
        $payload = $request->all();

        /*
        |--------------------------------------------------------------------------
        | Step 2: Extract event type
        |--------------------------------------------------------------------------
        */
        $event = $payload['event'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Step 3: Process webhook based on event
        |--------------------------------------------------------------------------
        */
        if ($event === 'user.created') {

            $user = $payload['user'];

            // Example: store or process data
            \Log::info('User received via webhook', $user);
            // \Log::info('Webhook payload:', $payload);

            $zohoController = new ZohoController();

            $zohoRequest = new Request($user);

            $zohoController->createCustomer($zohoRequest);
        }

        /*
        |--------------------------------------------------------------------------
        | Step 4: Always return 200 response
        |--------------------------------------------------------------------------
        | If 200 is not returned, sender will retry
        */
        return response()->json([
            'status' => 'success',
            'message' => 'Webhook received'
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Zoho\ZohoCustomerService;
use Illuminate\Support\Facades\Log;

class WebhookReceiverController extends Controller
{
    protected $service;

    public function __construct(ZohoCustomerService $service)
    {
        $this->service = $service;
    }

    public function receive(Request $request)
    {
        $payload = $request->all();

        Log::info("Webhook received", $payload);

        $event = $payload['event'] ?? null;

        if ($event === 'user.created') {

            $user = $payload['user'];

            $this->service->createCustomer($user);
        }


        if ($event === 'user.updated') {

            $user = $payload['user'];

            $this->service->updateCustomer($user);
        }

        if ($event === 'user.deleted') {

            $user = $payload['user'];

            $this->service->deleteCustomer($user);
        }


        return response()->json([
            'status' => 'success'
        ], 200);
    }
}

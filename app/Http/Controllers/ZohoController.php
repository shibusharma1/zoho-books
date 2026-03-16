<?php

namespace App\Http\Controllers;

use App\Services\Zoho\ZohoCustomerService;
use App\Http\Requests\CreateCustomerRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ZohoController extends Controller
{
    protected $service;

    public function __construct(ZohoCustomerService $service)
    {
        $this->service = $service;
    }

    public function redirectToZoho()
    {
        $client_id = config('services.zoho.client_id');
        $redirect_uri = config('services.zoho.redirect');

        $scope = "ZohoBooks.fullaccess.all";
        $access_type = "offline";

        $url = "https://accounts.zoho.com/oauth/v2/auth?"
            . "scope={$scope}"
            . "&client_id={$client_id}"
            . "&response_type=code"
            . "&access_type={$access_type}"
            . "&redirect_uri={$redirect_uri}";

        // Log::info("Redirecting to Zoho OAuth");

        return redirect($url);
    }


    public function handleZohoCallback(Request $request)
    {
        $code = $request->code;

        // Log::info("Zoho OAuth code received", ['code' => $code]);

        $response = Http::asForm()->post(
            'https://accounts.zoho.com/oauth/v2/token',
            [
                'grant_type' => 'authorization_code',
                'client_id' => config('services.zoho.client_id'),
                'client_secret' => config('services.zoho.client_secret'),
                'redirect_uri' => config('services.zoho.redirect'),
                'code' => $code
            ]
        );

        $data = $response->json();

        // Log::info("Zoho token response", $data);

        session([
            'zoho_access_token' => $data['access_token'],
            // 'zoho_refresh_token' => $data['refresh_token'],
            'zoho_token_expiry' => now()->addSeconds($data['expires_in'])
        ]);

        return $data;
    }

    public function organizations()
    {
        $accessToken = "1000.d558d8aa49b94b15e6dfacd734905097.b3497d5fd3e46c5925ac72d9468c342d";

        $response = Http::withToken($accessToken)
            ->get('https://www.zohoapis.com/books/v3/organizations');

        $data = $response->json();

        if (isset($data['organizations'][0]['organization_id'])) {
            session([
                'zoho_organization_id' => $data['organizations'][0]['organization_id']
            ]);
        }

        return $data;
    }

    public function customers()
    {
        $accessToken = session('zoho_access_token');
        $orgId = session('zoho_organization_id');

        $response = Http::withToken($accessToken)
            ->get(
                "https://www.zohoapis.com/books/v3/contacts",
                [
                    'organization_id' => $orgId
                ]
            );

        return $response->json();
    }

    public function createCustomer(CreateCustomerRequest $request)
    {
        try {

            $customer = $this->service->createCustomer($request->validated());

            Log::info("Customer created successfully");

            return response()->json($customer);
        } catch (\Exception $e) {

            Log::error("Zoho Customer Creation Failed", [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Customer creation failed'
            ], 500);
        }
    }
}

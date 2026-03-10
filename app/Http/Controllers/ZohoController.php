<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ZohoController extends Controller
{

    private function getValidAccessToken()
    {
        $accessToken = session('zoho_access_token');
        $expiry = session('zoho_token_expiry');

        // If token exists and not expired → use it
        if ($accessToken && $expiry && now()->lessThan($expiry)) {
            return $accessToken;
        }

        // Token missing or expired → redirect to Zoho login
        redirect('/zoho/connect')->send();
        exit;
    }

    // connect to the zoho application as per the client_id and client_secret in the env
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

        return redirect($url);
    }

    public function handleZohoCallback(Request $request)
    {
        $code = $request->code;

        $response = Http::asForm()->post('https://accounts.zoho.com/oauth/v2/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.zoho.client_id'),
            'client_secret' => config('services.zoho.client_secret'),
            'redirect_uri' => config('services.zoho.redirect'),
            'code' => $code
        ]);

        $data = $response->json();

        // dd($data);

        session([
            'zoho_access_token' => $data['access_token'],

            'zoho_token_expiry' => now()->addSeconds($data['expires_in'])
        ]);

        // dd(session('zoho_access_token'));

        return $data;
    }

    // function to get the organization ID
    public function organizations()
    {
        $accessToken = $this->getValidAccessToken();

        $response = Http::withToken($accessToken)
            ->get('https://www.zohoapis.com/books/v3/organizations');

        $data = $response->json();

        if (isset($data['organizations'][0]['organization_id'])) {
            $orgId = $data['organizations'][0]['organization_id'];

            session([
                'zoho_organization_id' => $orgId
            ]);
        }

        return $response->json();
    }


    // CRUD functions for the customer
    public function customers()
    {
        $accessToken = $this->getValidAccessToken();
        $orgId = session('zoho_organization_id');

        $response = Http::withToken($accessToken)
            ->get("https://www.zohoapis.com/books/v3/customers", [
                'organization_id' => $orgId
            ]);

        return $response->json();
    }

    public function createCustomerForm()
    {
        return view('zoho.create_customer');
    }

    public function createCustomer(Request $request)
    {
        $accessToken = $this->getValidAccessToken();
        $orgId = session('zoho_organization_id');

        $response = Http::withToken($accessToken)
            ->post("https://www.zohoapis.com/books/v3/contacts?organization_id={$orgId}", [
                "contact_name" => $request->contact_name,
                "company_name" => $request->company_name,
                "contact_type" => "customer",

                "contact_persons" => [
                    [
                        "first_name" => $request->contact_name,
                        "email" => $request->email,
                        "phone" => $request->phone,
                        "is_primary_contact" => true
                    ]
                ]
            ]);

        return $response->json();
    }

    public function editCustomer($id)
    {
        $accessToken = $this->getValidAccessToken();
        $orgId = session('zoho_organization_id');

        $response = Http::withToken($accessToken)
            ->get("https://www.zohoapis.com/books/v3/contacts/{$id}", [
                'organization_id' => $orgId
            ]);

        // dd($response->json());

        $customer = $response->json()['contact'];

        return view('zoho.edit_customer', compact('customer'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $accessToken = $this->getValidAccessToken();
        $orgId = session('zoho_organization_id');

        $response = Http::withToken($accessToken)
            ->put("https://www.zohoapis.com/books/v3/contacts/{$id}?organization_id={$orgId}", [
                "contact_name" => $request->contact_name,
                "company_name" => $request->company_name,
                "contact_type" => "customer",

                "contact_persons" => [
                    [
                        "first_name" => $request->contact_name,
                        "email" => $request->email,
                        "phone" => $request->phone,
                        "is_primary_contact" => true
                    ]
                ]
            ]);

        return $response->json();
    }

    public function deleteCustomer($id)
    {
        $accessToken = $this->getValidAccessToken();
        $orgId = session('zoho_organization_id');

        $response = Http::withToken($accessToken)
            ->delete("https://www.zohoapis.com/books/v3/contacts/{$id}", [
                'organization_id' => $orgId
            ]);

        return $response->json();
    }

    // CRUD operation for the taxes
    public function taxList()
{
    $accessToken = $this->getValidAccessToken();
    $orgId = session('zoho_organization_id');

    $response = Http::withToken($accessToken)
        ->get("https://www.zohoapis.com/books/v3/settings/taxes", [
            'organization_id' => $orgId
        ]);

    return $response->json();
}
public function createTaxForm()
{
    return view('zoho.tax.create');
}
public function storeTax(Request $request)
{
    $accessToken = $this->getValidAccessToken();
    $orgId = session('zoho_organization_id');

    $response = Http::withToken($accessToken)
        ->post("https://www.zohoapis.com/books/v3/settings/taxes?organization_id={$orgId}", [
            "tax_name" => $request->tax_name,
            "tax_percentage" => $request->tax_percentage
        ]);

    return $response->json();
}
public function editTaxForm($id)
{
    $accessToken = $this->getValidAccessToken();
    $orgId = session('zoho_organization_id');

    $response = Http::withToken($accessToken)
        ->get("https://www.zohoapis.com/books/v3/settings/taxes/{$id}", [
            'organization_id' => $orgId
        ]);

    $tax = $response->json()['tax'];

    return view('zoho.tax.edit', compact('tax'));
}
public function updateTax(Request $request, $id)
{
    $accessToken = $this->getValidAccessToken();
    $orgId = session('zoho_organization_id');

    $response = Http::withToken($accessToken)
        ->put("https://www.zohoapis.com/books/v3/settings/taxes/{$id}?organization_id={$orgId}", [
            "tax_name" => $request->tax_name,
            "tax_percentage" => $request->tax_percentage
        ]);

    return $response->json();
}
public function deleteTax($id)
{
    $accessToken = $this->getValidAccessToken();
    $orgId = session('zoho_organization_id');

    $response = Http::withToken($accessToken)
        ->delete("https://www.zohoapis.com/books/v3/settings/taxes/{$id}", [
            'organization_id' => $orgId
        ]);

    return $response->json();
}
}

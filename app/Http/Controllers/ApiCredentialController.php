<?php

namespace App\Http\Controllers;

use App\Models\ApiCredential;
use Illuminate\Http\Request;

class ApiCredentialController extends Controller
{
    // Show form for create/edit
    public function form($service_type = null)
    {

        // Find existing record for service_type
        $credential = null;
        if ($service_type) {
            $credential = ApiCredential::where('service_type', $service_type)
                ->where('company_id', 1)
                ->first();
        }

        return view('api_credentials.form', compact('credential', 'service_type'));
    }

    // Save or update
    public function save(Request $request)
    {

        $request->validate([
            'client_id' => 'required|string|max:255',
            'client_secret' => 'required|string|max:255',
            'redirect_uri' => 'required|url',
            'accounts_url' => 'required|url',
            'api_base' => 'required|url',
            'service_type' => 'required|string|max:50',
        ]);

        // dd($request->all());

        $data = $request->only([
            'client_id',
            'client_secret',
            'redirect_uri',
            'accounts_url',
            'api_base',
            'service_type',
            'access_token',
            'expired_at',
            'refresh_token',
            'revoked_at',
            'oauth_type',
            'scope',
        ]);

        // Add defaults
        $data['company_id'] = 1;
        $data['organization_id'] = 1;
        $data['created_by'] = 1;

        // Check if record exists
        $credential = ApiCredential::where('service_type', $data['service_type'])
            ->where('company_id', 1)
            ->first();

        if ($credential) {
            $credential->update($data);
            $message = 'API Credential updated successfully!';
        } else {
            ApiCredential::create($data);
            $message = 'API Credential created successfully!';
        }

        return redirect()->back()->with('success', $message);
    }
}

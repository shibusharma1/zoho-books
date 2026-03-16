<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;

class ZohoTokenProvider
{
    public function getAccessToken()
    {
        $token = "1000.d558d8aa49b94b15e6dfacd734905097.b3497d5fd3e46c5925ac72d9468c342d";

        if (!$token) {
            Log::error("Zoho access token missing");
            throw new \Exception("Zoho token missing");
        }

        return $token;
    }

    public function getOrganizationId()
    {
        $orgId = "917123495";

        if (!$orgId) {
            Log::error("Zoho organization id missing");
            throw new \Exception("Zoho organization missing");
        }

        return $orgId;
    }
}
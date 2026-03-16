<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ZohoApiRequest
{
    protected function zohoGet($url, $token, $params = [])
    {
        Log::info("Zoho GET Request", ['url' => $url, 'params' => $params]);

        return Http::withToken($token)->get($url, $params)->json();
    }

    protected function zohoPost($url, $token, $payload = [])
    {
        Log::info("Zoho POST Request", ['url' => $url, 'payload' => $payload]);

        return Http::withToken($token)->post($url, $payload)->json();
    }

    protected function zohoPut($url, $token, $payload = [])
    {
        Log::info("Zoho PUT Request", ['url' => $url, 'payload' => $payload]);

        return Http::withToken($token)->put($url, $payload)->json();
    }

    protected function zohoDelete($url, $token, $params = [])
    {
        Log::info("Zoho DELETE Request", ['url' => $url]);

        return Http::withToken($token)->delete($url, $params)->json();
    }
}
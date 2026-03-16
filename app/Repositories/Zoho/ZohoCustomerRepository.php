<?php

namespace App\Repositories\Zoho;

use App\Traits\ZohoApiRequest;
use App\Providers\ZohoTokenProvider;
use Illuminate\Support\Facades\Log;

class ZohoCustomerRepository
{
    use ZohoApiRequest;

    protected $tokenProvider;

    public function __construct(ZohoTokenProvider $tokenProvider)
    {
        $this->tokenProvider = $tokenProvider;
    }

    public function create(array $data)
    {
        $token = $this->tokenProvider->getAccessToken();
        $orgId = $this->tokenProvider->getOrganizationId();

        $url = "https://www.zohoapis.com/books/v3/contacts?organization_id={$orgId}";

        $response = $this->zohoPost($url, $token, $data);

        Log::info("Zoho customer created", $response);

        return $response;
    }

    public function list()
    {
        $token = $this->tokenProvider->getAccessToken();
        $orgId = $this->tokenProvider->getOrganizationId();

        return $this->zohoGet(
            "https://www.zohoapis.com/books/v3/contacts",
            $token,
            ['organization_id' => $orgId]
        );
    }

    public function update($id, $data)
    {
        $token = $this->tokenProvider->getAccessToken();
        $orgId = $this->tokenProvider->getOrganizationId();

        $url = "https://www.zohoapis.com/books/v3/contacts/{$id}?organization_id={$orgId}";

        return $this->zohoPut($url, $token, $data);
    }

    public function delete($id)
    {
        $token = $this->tokenProvider->getAccessToken();
        $orgId = $this->tokenProvider->getOrganizationId();

        $url = "https://www.zohoapis.com/books/v3/contacts/{$id}";

        return $this->zohoDelete($url, $token, ['organization_id'=>$orgId]);
    }
}
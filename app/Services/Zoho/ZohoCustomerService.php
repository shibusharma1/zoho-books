<?php

namespace App\Services\Zoho;

use App\Repositories\Zoho\ZohoCustomerRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZohoCustomerService
{
    protected $repo;

    public function __construct(ZohoCustomerRepository $repo)
    {
        $this->repo = $repo;
    }

    // public function createCustomer($data)
    // {
    //     Log::info("Creating Zoho Customer", $data);

    //     $payload = [
    //         "contact_name" => $data['contact_name'],
    //         "company_name" => $data['company_name'],
    //         "contact_type" => "customer",
    //         "contact_persons" => [
    //             [
    //                 "first_name"=>$data['contact_name'],
    //                 "email"=>$data['email'],
    //                 "phone"=>$data['phone'],
    //                 "is_primary_contact"=>true
    //             ]
    //         ]
    //     ];

    //     return $this->repo->create($payload);
    // }

    public function createCustomer($data)
    {
        Log::info("Creating Zoho customer", $data);

        $response = $this->repo->create([
            "contact_name" => $data['contact_name'],
            "company_name" => $data['company_name'],
            "contact_type" => "customer",
            "contact_persons" => [
                [
                    "first_name" => $data['contact_name'],
                    "email" => $data['email'],
                    "phone" => $data['phone'],
                    "is_primary_contact" => true
                ]
            ]
        ]);

        $zoho_id = $response['contact']['contact_id'] ?? null;

        Log::info("Zoho ID received", [
            'zoho_id' => $zoho_id
        ]);

        if (!$zoho_id) {

            Log::error("Zoho contact creation failed", [
                'response' => $response
            ]);

            throw new \Exception("Zoho contact creation failed");
        }

        $this->updateExternalCustomer($data['id'], $zoho_id);

        return $response;
    }

    /* Update a Zoho customer and sync with local system.
     */
    public function updateCustomer(array $data)
    {
        Log::info("Updating Zoho customer", $data);

        if (empty($data['zb_id'])) {
            throw new \Exception("Zoho ID (zb_id) is required to update customer");
        }

        $response = $this->repo->update($data['zb_id'], [
            "contact_name" => $data['contact_name'],
            "company_name" => $data['company_name'],
            "contact_persons" => [
                [
                    "first_name" => $data['contact_name'],
                    "email" => $data['email'],
                    "phone" => $data['phone'],
                    "is_primary_contact" => true
                ]
            ]
        ]);

        $zoho_id = $response['contact']['contact_id'] ?? null;

        if (!$zoho_id) {
            Log::error("Zoho contact update failed", ['response' => $response]);
            throw new \Exception("Zoho contact update failed");
        }

        $this->updateExternalCustomer($data['id'], $zoho_id);

        Log::info("Zoho customer updated successfully", ['zoho_id' => $zoho_id]);

        return $response;
    }

    /**
     * Delete a Zoho customer and optionally update local system.
     */
    public function deleteCustomer(array $data)
    {
        Log::info("Deleting Zoho customer", $data);

        if (empty($data['zb_id'])) {
            throw new \Exception("Zoho ID (zb_id) is required to delete customer");
        }

        $response = $this->repo->delete($data['zb_id']);

        if (!isset($response['code']) || $response['code'] !== 0) {
            Log::error("Zoho contact deletion failed", ['response' => $response]);
            throw new \Exception("Zoho contact deletion failed");
        }

        // Optionally clear zb_id from local system
        // $this->updateExternalCustomer($data['id'], null);

        Log::info("Zoho customer deleted successfully", ['customer_id' => $data['id']]);

        return $response;
    }

    protected function updateExternalCustomer($id, $zoho_id)
    {
        Log::info("Updating external system with Zoho ID", [
            'customer_id' => $id,
            'zoho_id' => $zoho_id
        ]);

        $response = Http::asJson()->patch(
            "http://127.0.0.1:8000/api/customers/{$id}/zb-id",
            ['zb_id' => $zoho_id]
        );

        if ($response->successful()) {

            Log::info("External system updated successfully", [
                'response' => $response->json()
            ]);
        } else {

            Log::error("External system update failed", [
                'response' => $response->body()
            ]);
        }
    }
}

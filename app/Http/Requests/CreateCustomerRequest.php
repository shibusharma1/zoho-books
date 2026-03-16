<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'contact_name'=>'required|string',
            'company_name'=>'nullable|string',
            'email'=>'required|email',
            'phone'=>'nullable|string'
        ];
    }
}
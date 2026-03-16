<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiCredential extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'organization_id',
        'client_id',
        'client_secret',
        'redirect_uri',
        'accounts_url',
        'api_base',
        'access_token',
        'expired_at',
        'refresh_token',
        'revoked_at',
        'service_type',
        'oauth_type',
        'scope',
        'created_by',
    ];
}
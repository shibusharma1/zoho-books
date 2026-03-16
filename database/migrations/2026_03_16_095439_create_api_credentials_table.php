<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('client_id', 255);
            $table->string('client_secret', 255);
            $table->string('redirect_uri', 255);
            $table->string('accounts_url', 255);
            $table->string('api_base', 255);
            $table->string('access_token', 255)->nullable();
            $table->timestamp('expired_at')->nullable()->comment('access token expired time');
            $table->string('refresh_token', 255)->nullable();
            $table->timestamp('revoked_at')->nullable()->comment('refresh token revoked time');
            $table->string('service_type', 50)->comment('zb=zoho books, qb=quickbooks etc');
            $table->string('oauth_type', 50)->nullable();
            $table->string('scope', 255)->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_credentials');
    }
};

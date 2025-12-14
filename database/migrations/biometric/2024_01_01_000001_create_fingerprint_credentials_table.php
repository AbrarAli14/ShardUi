<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fingerprint_credentials', function (Blueprint $table) {
            $table->id();
            
            // User and device relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_id')->unique();
            
            // WebAuthn credential data
            $table->string('credential_id')->unique();
            $table->json('public_key');
            $table->json('credential_data');
            
            // Device and credential information
            $table->string('device_type'); // mobile, tablet, kiosk, workstation
            $table->string('authenticator_type'); // platform, cross-platform
            $table->string('algorithm'); // ES256, RS256, etc.
            
            // Status and metadata
            $table->boolean('active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->integer('usage_count')->default(0);
            
            // Security and audit
            $table->string('ip_address', 45);
            $table->text('user_agent');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'active']);
            $table->index('credential_id');
            $table->index('device_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fingerprint_credentials');
    }
};

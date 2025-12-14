<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_devices', function (Blueprint $table) {
            $table->id();
            
            // Device identification
            $table->string('device_id')->unique();
            $table->string('name');
            $table->string('type'); // mobile, tablet, kiosk, workstation
            
            // Device capabilities
            $table->json('capabilities')->nullable();
            $table->json('supported_methods'); // ["fingerprint", "face", "voice"]
            $table->json('hardware_info')->nullable();
            
            // Device registration
            $table->foreignId('registered_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('registered_at');
            $table->string('registration_ip', 45);
            
            // Status and location
            $table->boolean('active')->default(true);
            $table->string('location')->nullable();
            $table->json('geo_location')->nullable();
            
            // Security settings
            $table->boolean('require_user_verification')->default(true);
            $table->integer('max_attempts')->default(3);
            $table->integer('lockout_minutes')->default(15);
            
            // Shard assignment settings
            $table->boolean('auto_assign_shards')->default(true);
            $table->json('default_shards')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['type', 'active']);
            $table->index('registered_by');
            $table->index('device_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_devices');
    }
};

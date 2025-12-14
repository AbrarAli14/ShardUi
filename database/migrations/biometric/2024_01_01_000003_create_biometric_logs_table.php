<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();
            
            // Authentication information
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_id');
            $table->string('method'); // fingerprint, face, voice
            $table->string('action'); // register, authenticate, assign_shards
            
            // Result and details
            $table->boolean('success');
            $table->string('status_code')->nullable();
            $table->text('error_message')->nullable();
            
            // Request information
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->json('request_data')->nullable();
            
            // Response information
            $table->json('response_data')->nullable();
            $table->integer('response_time_ms')->nullable();
            
            // Shard assignment details
            $table->json('assigned_shards')->nullable();
            $table->integer('shards_count')->default(0);
            
            // Security and audit
            $table->string('session_id')->nullable();
            $table->json('security_flags')->nullable();
            
            $table->timestamp('created_at');
            
            // Indexes for performance and analytics
            $table->index(['user_id', 'created_at']);
            $table->index(['device_id', 'created_at']);
            $table->index(['method', 'success']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_logs');
    }
};

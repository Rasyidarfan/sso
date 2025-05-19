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
        Schema::create('auth_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('client_id');
            $table->foreignId('user_id')->constrained();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
            
            // Foreign key to client_apps
            $table->foreign('client_id')->references('client_id')->on('client_apps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_codes');
    }
};

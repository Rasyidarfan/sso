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
        Schema::table('client_apps', function (Blueprint $table) {
            $table->string('redirect_uri_dev')->nullable()->after('redirect_uri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_apps', function (Blueprint $table) {
            $table->dropColumn('redirect_uri_dev');
        });
    }
};

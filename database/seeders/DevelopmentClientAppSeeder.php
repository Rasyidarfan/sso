<?php

namespace Database\Seeders;

use App\Models\ClientApp;
use Illuminate\Database\Seeder;

class DevelopmentClientAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create development client app
        ClientApp::create([
            'name' => 'SOP Development',
            'client_id' => 'dev_sop_app',
            'client_secret' => 'dev_secret_123456789',
            'redirect_uri' => 'http://127.0.0.1:8000/auth',
            'description' => 'Development client for SOP application',
            'is_active' => true,
            'created_by' => 1,
        ]);

        ClientApp::create([
            'name' => 'Test Client Local',
            'client_id' => 'test_local_123',
            'client_secret' => 'test_secret_local_456',
            'redirect_uri' => 'http://localhost:3000/callback',
            'description' => 'Test client for local development',
            'is_active' => true,
            'created_by' => 1,
        ]);
    }
}

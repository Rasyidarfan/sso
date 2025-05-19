<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator dengan akses penuh'],
            ['name' => 'umum', 'description' => 'Bagian umum yang dapat mengelola data pegawai'],
            ['name' => 'kepala', 'description' => 'Kepala Unit'],
            ['name' => 'neraca', 'description' => 'Tim Neraca'],
            ['name' => 'produksi', 'description' => 'Tim Produksi'],
            ['name' => 'distribusi', 'description' => 'Tim Distribusi'],
            ['name' => 'sosial', 'description' => 'Tim Sosial'],
            ['name' => 'ipds', 'description' => 'Tim IPDS (Pengolahan Data)'],
            ['name' => 'humas', 'description' => 'Tim Humas'],
            ['name' => 'descan', 'description' => 'Tim Descan'],
            ['name' => 'rb', 'description' => 'Tim RB'],
            ['name' => 'nerwilis', 'description' => 'Tim Nerwilis'],
            ['name' => 'pengolahan', 'description' => 'Tim Pengolahan'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Ahmad Arfan Arsyad S.Tr.Stat.',
            'email' => 'arfan@bps.go.id',
            'password' => Hash::make('password'),
            'nip9' => '340061633',
            'nip16' => '200002042023021002',
        ]);

        // Assign admin role
        $admin->roles()->attach(Role::where('name', 'admin')->first()->id);
        $admin->roles()->attach(Role::where('name', 'ipds')->first()->id);
        $admin->roles()->attach(Role::where('name', 'nerwilis')->first()->id);
        
        // Seed from pegawai data
        $this->call(PegawaiSeeder::class);
    }
}

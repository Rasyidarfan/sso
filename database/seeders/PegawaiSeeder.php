<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawai = [
            [
                'name' => 'Arther Ludwig Purmiasa SP',
                'email' => 'arther@bps.go.id',
                'nip9' => '340016810',
                'nip16' => '197107012002121003',
                'roles' => ['kepala']
            ],
            [
                'name' => 'Maria Erdina Ohoitimur S.E.',
                'email' => 'maria.ohoitimur@bps.go.id',
                'nip9' => '340055640',
                'nip16' => '198507212011012018',
                'roles' => ['umum']
            ],
            [
                'name' => 'Robert Willem Bobby Talubun S.AP',
                'email' => 'robert.talubun@bps.go.id',
                'nip9' => '340020516',
                'nip16' => '197210232007101002',
                'roles' => ['neraca', 'descan']
            ],
            [
                'name' => 'Wopi Welius Siep',
                'email' => 'welius@bps.go.id',
                'nip9' => '340018719',
                'nip16' => '197803302006041014',
                'roles' => ['produksi', 'descan']
            ],
            [
                'name' => 'Eka Putra Setiawan SE, M.Ec.Dev.',
                'email' => 'eka.setiawan@bps.go.id',
                'nip9' => '340053984',
                'nip16' => '198804252010031001',
                'roles' => ['distribusi']
            ],
            [
                'name' => 'Fatkhur Rahman SST',
                'email' => 'fatkhur.rahman@bps.go.id',
                'nip9' => '340057060',
                'nip16' => '199210182014121001',
                'roles' => ['umum']
            ],
            [
                'name' => 'Ahmad Fauzan S.Tr.Stat.',
                'email' => 'ahmad.fauzan@bps.go.id',
                'nip9' => '340062311',
                'nip16' => '200103052023101002',
                'roles' => ['produksi', 'humas', 'rb']
            ],
            [
                'name' => 'Athiyya Eka Dewanti S.Tr.Stat.',
                'email' => 'athiyya.eka@bps.go.id',
                'nip9' => '340062362',
                'nip16' => '200107152023102003',
                'roles' => ['sosial']
            ],
            [
                'name' => 'Shintia Ananda Owu A.Md.Stat.',
                'email' => 'anandashintia@bps.go.id',
                'nip9' => '340062702',
                'nip16' => '200211142023102003',
                'roles' => ['umum', 'humas']
            ],
        ];

        foreach ($pegawai as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'nip9' => $data['nip9'],
                'nip16' => $data['nip16'],
            ]);

            // Assign roles
            foreach ($data['roles'] as $roleName) {
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            }
        }
    }
}

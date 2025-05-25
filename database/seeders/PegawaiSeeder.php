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
            [
                'name' => 'Puridin Situmorang S.Sos.',
                'email' => 'puridin.situmorang@bps.go.id',
                'nip9' => '340012275',
                'nip16' => '196608051989031004',
                'roles' => ['produksi']
            ],
            [
                'name' => 'A. Majid S.Sos',
                'email' => 'amajid@bps.go.id',
                'nip9' => '340012307',
                'nip16' => '196812311989031014',
                'roles' => ['sosial']
            ],
            [
                'name' => 'Alfonsina Yumame S.E.',
                'email' => 'alfonsina.yumame@bps.go.id',
                'nip9' => '340055609',
                'nip16' => '198502202011012010',
                'roles' => ['distribusi']
            ],
            [
                'name' => 'Betsy Batseba Donggori SST',
                'email' => 'betsydonggori@bps.go.id',
                'nip9' => '340056230',
                'nip16' => '198912282013112001',
                'roles' => ['produksi']
            ],
            [
                'name' => 'Rio Fernando Aroy',
                'email' => 'rio.aroy@bps.go.id',
                'nip9' => '340055655',
                'nip16' => '199009292011011001',
                'roles' => ['neraca', 'pengolahan']
            ],
            [
                'name' => 'Ario Wicaksono SST',
                'email' => 'ario.wicaksono@bps.go.id',
                'nip9' => '340058042',
                'nip16' => '199407242017011001',
                'roles' => ['neraca']
            ],
            [
                'name' => 'Jezenia Jaqueline Rolian Karet S.Tr.Stat.',
                'email' => 'jezeniakrth@bps.go.id',
                'nip9' => '340058793',
                'nip16' => '199609012019012001',
                'roles' => ['distribusi', 'humas']
            ],
            [
                'name' => 'Prawesty Dian Utami S.Tr.Stat.',
                'email' => 'prawesty.utami@bps.go.id',
                'nip9' => '340060272',
                'nip16' => '199710242021042001',
                'roles' => ['umum', 'rb']
            ],
            [
                'name' => 'Sahara Sabilah Putri S.Tr.Stat.',
                'email' => 'sahara.putri@bps.go.id',
                'nip9' => '340060310',
                'nip16' => '199804242021042001',
                'roles' => ['distribusi']
            ],
            [
                'name' => 'Khairunissa Balqis Zhahira S.Tr.Stat.',
                'email' => 'balqiszhahira@bps.go.id',
                'nip9' => '340060712',
                'nip16' => '199805052022012001',
                'roles' => ['umum', 'humas']
            ],
            [
                'name' => 'Lanang Adi Berkah S.Tr.Stat.',
                'email' => 'lanang.adi@bps.go.id',
                'nip9' => '340060715',
                'nip16' => '199807022022011001',
                'roles' => ['pengolahan', 'descan']
            ],
            [
                'name' => 'Ahmad Arfan Arsyad S.Tr.Stat.',
                'email' => 'aarfanarsyad@bps.go.id',
                'nip9' => '340061633',
                'nip16' => '200002042023021002',
                'roles' => ['admin', 'pengolahan', 'humas']
            ],
            [
                'name' => 'Joni Kidang S.Tr.Stat.',
                'email' => 'joni.kidang@bps.go.id',
                'nip9' => '340055636',
                'nip16' => '198706202011011010',
                'roles' => ['neraca', 'rb']
            ],
            [
                'name' => 'Rachel Lyberti Mayasiah Rumadas S.Tr.Stat',
                'email' => 'rachel.lyberti@bps.go.id',
                'nip9' => '340058918',
                'nip16' => '199603122019012001',
                'roles' => ['sosial']
            ],
            [
                'name' => 'Lady Deborah S.Tr.Stat',
                'email' => 'lady.deborah@bps.go.id',
                'nip9' => '340055639',
                'nip16' => '198702132011012015',
                'roles' => ['distribusi']
            ],
            [
                'name' => 'Dwika Gielsen Nugraha A.Md.Stat.',
                'email' => 'dwika.nugraha@bps.go.id',
                'nip9' => '340063122',
                'nip16' => '200304152024121001',
                'roles' => ['umum', 'humas']
            ],
            [
                'name' => 'Indramawan Yusuf Adi Prayoga S.Tr.Stat.',
                'email' => 'indramawan.prayoga@bps.go.id',
                'nip9' => '340063228',
                'nip16' => '200106252024121004',
                'roles' => ['produksi', 'humas']
            ],
            [
                'name' => 'Rayhan Ardiya Januprasetya S.Tr.Stat.',
                'email' => 'rayhan.ardiya@bps.go.id',
                'nip9' => '340063472',
                'nip16' => '200101122024121001',
                'roles' => ['sosial', 'humas']
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

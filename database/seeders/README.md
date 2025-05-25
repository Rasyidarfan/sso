# Database Seeder Instructions

## PegawaiSeeder Setup

File `PegawaiSeeder.php` berisi data sensitif pegawai dan tidak disertakan dalam repository git untuk alasan keamanan.

### Setup untuk Development:

1. **Copy template file:**
   ```bash
   cp database/seeders/PegawaiSeeder.php.template database/seeders/PegawaiSeeder.php
   ```

2. **Edit file `PegawaiSeeder.php`:**
   - Buka file `database/seeders/PegawaiSeeder.php`
   - Ganti data template dengan data pegawai yang sesungguhnya
   - Pastikan email dan NIP unik untuk setiap pegawai

3. **Jalankan seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

### Setup untuk Production:

1. Pastikan file `PegawaiSeeder.php` ada di server production
2. File ini harus berisi data pegawai yang valid
3. Jalankan migration dan seeder di production

### Available Roles:

- `admin` - Administrator dengan akses penuh
- `umum` - Bagian umum yang dapat mengelola data pegawai  
- `kepala` - Kepala Unit
- `neraca` - Tim Neraca
- `produksi` - Tim Produksi
- `distribusi` - Tim Distribusi
- `sosial` - Tim Sosial
- `ipds` - Tim IPDS (Pengolahan Data)
- `humas` - Tim Humas
- `descan` - Tim Descan
- `rb` - Tim RB
- `nerwilis` - Tim Nerwilis
- `pengolahan` - Tim Pengolahan

### Format Data Pegawai:

```php
[
    'name' => 'Nama Lengkap Pegawai',
    'email' => 'email@bps.go.id',
    'nip9' => '123456789', // 9 digit
    'nip16' => '123456789012345678', // 18 digit
    'roles' => ['admin', 'ipds'] // Array role names
]
```

### Security Note:

- File `PegawaiSeeder.php` tidak akan di-commit ke git
- Pastikan tidak membagikan file ini di public repository
- Gunakan password yang kuat untuk environment production

# SSO BPS - Single Sign-On System

## Deskripsi

SSO BPS adalah sistem otentikasi terpusat untuk aplikasi internal BPS. Sistem ini memungkinkan pengguna untuk melakukan login sekali dan mendapatkan akses ke semua aplikasi yang terhubung.

## Fitur Utama

- **Single Sign-On**: Login sekali untuk semua aplikasi
- **Manajemen User**: Pengelolaan akun dan role pengguna
- **Manajemen Aplikasi**: Pendaftaran dan pengelolaan aplikasi klien
- **API OAuth**: Integrasi mudah dengan aplikasi internal

## Prasyarat

- PHP 8.1+
- MySQL 5.7+
- Composer
- Web Server (Apache/Nginx)

## Instalasi

1. Clone repositori ini
2. Install dependensi menggunakan Composer
   ```
   composer install
   ```
3. Salin file .env.example menjadi .env dan atur konfigurasi database
4. Generate application key
   ```
   php artisan key:generate
   ```
5. Jalankan migrasi dan seeder
   ```
   php artisan migrate --seed
   ```
6. Jalankan aplikasi
   ```
   php artisan serve
   ```

## Penggunaan

### Login Admin

- URL: `/login`
- Email: `aarfanarsyad@bps.go.id`
- Password: `password`

### Dokumentasi API

Dokumentasi API dapat diakses di `/docs`

## Pengembangan

Aplikasi ini menggunakan Laravel 10 sebagai framework utama.

### Struktur Direktori Utama

- `app/Models`: Model database
- `app/Http/Controllers`: Controller aplikasi
- `app/Http/Middleware`: Middleware untuk kontrol akses
- `resources/views`: Template tampilan

### Alur SSO

1. Aplikasi klien mengarahkan pengguna ke `/v1/authorize` dengan parameter client_id dan state
2. Pengguna melakukan login atau langsung dialihkan jika sudah login
3. Sistem mengalihkan pengguna kembali ke aplikasi klien dengan authorization code
4. Aplikasi klien menukarkan code dengan data pengguna melalui endpoint `/v1/token`

## Lisensi

Aplikasi ini bersifat internal dan tidak untuk didistribusikan.

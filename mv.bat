@echo off
REM Buat proyek Laravel baru
composer require laravel/ui

REM Buat direktori yang dibutuhkan jika belum ada
mkdir app\Models
mkdir app\Http\Controllers\Auth
mkdir app\Http\Middleware
mkdir database\seeders
mkdir resources\views\auth
mkdir resources\views\users
mkdir resources\views\client_apps
mkdir resources\views\home
mkdir public\docs

REM Salin file model
xcopy /Y D:\Program\sso\app\Models\*.* D:\Program\sso-new\app\Models\

REM Salin file controller
xcopy /Y D:\Program\sso\app\Http\Controllers\*.* D:\Program\sso-new\app\Http\Controllers\
xcopy /Y D:\Program\sso\app\Http\Controllers\Auth\*.* D:\Program\sso-new\app\Http\Controllers\Auth\

REM Salin file middleware
xcopy /Y D:\Program\sso\app\Http\Middleware\*.* D:\Program\sso-new\app\Http\Middleware\

REM Salin file Kernel.php
xcopy /Y D:\Program\sso\app\Http\Kernel.php D:\Program\sso-new\app\Http\Kernel.php

REM Salin file migrasi
xcopy /Y D:\Program\sso\database\migrations\*.* D:\Program\sso-new\database\migrations\

REM Salin file seeder
xcopy /Y D:\Program\sso\database\seeders\*.* D:\Program\sso-new\database\seeders\

REM Salin file view
xcopy /Y /S D:\Program\sso\resources\views\auth\*.* D:\Program\sso-new\resources\views\auth\
xcopy /Y /S D:\Program\sso\resources\views\users\*.* D:\Program\sso-new\resources\views\users\
xcopy /Y /S D:\Program\sso\resources\views\client_apps\*.* D:\Program\sso-new\resources\views\client_apps\
xcopy /Y /S D:\Program\sso\resources\views\home\*.* D:\Program\sso-new\resources\views\home\
xcopy /Y D:\Program\sso\resources\views\layouts\*.* D:\Program\sso-new\resources\views\layouts\

REM Salin file route
xcopy /Y D:\Program\sso\routes\web.php D:\Program\sso-new\routes\web.php

REM Salin file dokumentasi API
xcopy /Y D:\Program\sso\public\docs\*.* D:\Program\sso-new\public\docs\

REM Salin file README
xcopy /Y D:\Program\sso\README.md D:\Program\sso-new\README.md

REM Salin file .env
xcopy /Y D:\Program\sso\.env D:\Program\sso-new\.env

echo.
echo Pemindahan file selesai!
echo.
echo Langkah selanjutnya:
echo 1. Jalankan: cd D:\Program\sso-new
echo 2. Jalankan: php artisan key:generate
echo 3. Jalankan: php artisan migrate --seed
echo 4. Jalankan: php artisan serve
echo.
@extends('layouts.app')

@section('title', 'Home - SSO BPS')

@section('content')
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Selamat Datang di SSO BPS</h4>
                <p>Single Sign-On (SSO) adalah sistem otentikasi terpusat yang memungkinkan pengguna mengakses berbagai aplikasi internal BPS dengan satu kali login.</p>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Kelola Akun
                                </h5>
                                <p class="card-text">Akses dan kelola informasi akun Anda.</p>
                                <a href="{{ route('profile') }}" class="btn btn-sm btn-primary">Lihat Profile</a>
                            </div>
                        </div>
                    </div>
                    
                    @if(Auth::user()->isAdmin())
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-users text-success me-2"></i>
                                    Manajemen Pengguna
                                </h5>
                                <p class="card-text">Tambah, edit, dan kelola akun pengguna.</p>
                                <a href="{{ route('users.index') }}" class="btn btn-sm btn-success">Kelola User</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-code text-info me-2"></i>
                                    API & Aplikasi
                                </h5>
                                <p class="card-text">Daftarkan dan kelola aplikasi yang terintegrasi dengan SSO.</p>
                                <a href="{{ route('client-apps.index') }}" class="btn btn-sm btn-info">Kelola Aplikasi</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-user-circle me-2"></i>
                    Profil Anda
                </h5>
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                        <div class="text-muted">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">NIP</small>
                    <div>{{ Auth::user()->nip9 }} / {{ Auth::user()->nip16 }}</div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Tim</small>
                    <div>
                        @foreach(Auth::user()->roles as $role)
                            <span class="role-tag">{{ ucfirst($role->name) }}</span>
                        @endforeach
                    </div>
                </div>
                
                <div class="d-grid">
                    <a href="{{ route('profile') }}" class="btn btn-outline-primary">
                        <i class="fas fa-cog me-2"></i> Pengaturan Profil
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Tentang SSO
                </h5>
                <p>Sistem SSO BPS memungkinkan pengguna melakukan autentikasi sekali dan mendapatkan akses ke semua aplikasi internal tanpa perlu login berulang kali.</p>
                <p class="mb-0">Versi: 1.0</p>
            </div>
        </div>
    </div>
</div>
@endsection

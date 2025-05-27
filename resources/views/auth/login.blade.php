@extends('layouts.auth')

@section('content')
<div class="login-container">
    <div class="left-side">
        <div class="logo">
            <img src="{{ asset('Logo_tagline.png') }}" alt="BPS Logo">
        </div>
        <h1 class="title">SINGLE SIGN-ON<br>BPS Kab Jayawijaya</h1>
        <p class="subtitle">Masukkan Email BPS dan Password</p>
    </div>
    <div class="right-side">
        <div class="sign-in-header">
            <h2 class="sign-in-title">SIGN IN</h2>
            <p class="sign-in-subtitle">TO ACCESS APPLICATION</p>
        </div>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('message'))
            <div class="alert alert-info">
                {{ session('message') }}
            </div>
        @endif
        
        {{-- Tampilkan info client app jika ada --}}
        @if(request('client_name'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                <strong>Login untuk mengakses:</strong> {{ request('client_name') }}
                <br><small>Masukkan kredensial BPS Anda untuk melanjutkan.</small>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            {{-- Hidden fields untuk SSO parameters --}}
            @if(request('client_id'))
                <input type="hidden" name="client_id" value="{{ request('client_id') }}">
            @endif
            @if(request('state'))
                <input type="hidden" name="state" value="{{ request('state') }}">
            @endif
            @if(request('redirect_after_login'))
                <input type="hidden" name="redirect_after_login" value="{{ request('redirect_after_login') }}">
            @endif
            @if(request('client_name'))
                <input type="hidden" name="client_name" value="{{ request('client_name') }}">
            @endif
            
            <div class="form-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group remember-me">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
            </div>
            <button type="submit" class="btn-login">Log In</button>
        </form>
    </div>
</div>
@endsection

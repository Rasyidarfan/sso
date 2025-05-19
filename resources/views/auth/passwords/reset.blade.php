@extends('layouts.auth')

@section('content')
<div class="login-container">
    <div class="left-side">
        <div class="logo">
            <img src="{{ asset('Logo_tagline.png') }}" alt="BPS Logo">
        </div>
        <h1 class="title">SINGLE SIGN-ON BPS</h1>
        <p class="subtitle">Update your password<br>to secure your account</p>
    </div>
    <div class="right-side">
        <div class="sign-in-header">
            <h2 class="sign-in-title">SET NEW PASSWORD</h2>
            <p class="sign-in-subtitle">SECURE YOUR ACCOUNT</p>
        </div>
        
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" required readonly>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="New Password" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" required>
            </div>
            
            <button type="submit" class="btn-login">Reset Password</button>
        </form>
    </div>
</div>
@endsection

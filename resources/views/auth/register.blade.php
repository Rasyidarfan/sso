@extends('layouts.auth')

@section('content')
<div class="login-container">
    <div class="left-side">
        <div class="logo">
            <img src="{{ asset('Logo_tagline.png') }}" alt="BPS Logo">
        </div>
        <h1 class="title">SINGLE SIGN-ON BPS</h1>
        <p class="subtitle">Enter your ID and<br>Password to continue</p>
    </div>
    <div class="right-side">
        <div class="sign-in-header">
            <h2 class="sign-in-title">REGISTER</h2>
            <p class="sign-in-subtitle">CREATE NEW ACCOUNT</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="text" class="form-control @error('nip9') is-invalid @enderror" name="nip9" value="{{ old('nip9') }}" placeholder="NIP 9 Digit" required>
                @error('nip9')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="text" class="form-control @error('nip16') is-invalid @enderror" name="nip16" value="{{ old('nip16') }}" placeholder="NIP 16 Digit" required>
                @error('nip16')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
            </div>
            
            <button type="submit" class="btn-login">Register</button>
            
            <div class="text-center mt-3">
                <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Dokumentasi API - SSO BPS')

@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="mb-4">Dokumentasi API SSO BPS</h2>
        
        @php
            use App\Helpers\SsoHelper;
            $currentApiUrl = SsoHelper::getApiBaseUrl();
            $currentDomain = SsoHelper::getApiDomain();
        @endphp

        <div class="alert alert-info">
            <strong>Current Environment:</strong> {{ config('app.env') }}<br>
            <strong>Base URL:</strong> {{ $currentApiUrl }}
        </div>

        <h3>Environment Configuration</h3>
        <ul>
            <li><strong>Development:</strong> http://127.0.0.1:8000/v1/</li>
            <li><strong>Production:</strong> https://sso.bps9702.com/v1/</li>
        </ul>

        <h3>Endpoints</h3>
        
        <h4>1. Authorize</h4>
        <p><code>GET {{ $currentDomain }}/v1/authorize</code></p>
        <p>Parameters: <code>client_id</code> (required), <code>state</code> (optional)</p>
        
        <h4>2. Token</h4>
        <p><code>POST {{ $currentDomain }}/v1/token</code></p>
        <p>Body: <code>code</code>, <code>client_id</code>, <code>client_secret</code></p>

        <div class="mt-4">
            <a href="{{ route('client-apps.index') }}" class="btn btn-primary">Kelola Aplikasi</a>
        </div>
    </div>
</div>
@endsection

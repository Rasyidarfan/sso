@extends('layouts.app')

@section('title', 'Daftar Aplikasi - SSO BPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Daftar Aplikasi SSO</h4>
    <div class="mb-0">
        <a href="docs" class="btn btn-secondary">
            <i class="fas fa-book"></i> Dokumentasi
        </a>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('client-apps.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Aplikasi
        </a>
        @endif
    </div>
</div>

<div class="row">
    @foreach($clientApps as $app)
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title">{{ $app->name }}</h5>
                    @if($app->is_active)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Client ID</small>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" value="{{ $app->client_id }}" readonly>
                        <button class="btn btn-outline-secondary btn-sm copy-btn" data-clipboard-text="{{ $app->client_id }}" type="button">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Redirect URI</small>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" value="{{ $app->redirect_uri }}" readonly>
                        <button class="btn btn-outline-secondary btn-sm copy-btn" data-clipboard-text="{{ $app->redirect_uri }}" type="button">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                
                @if($app->description)
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Deskripsi</small>
                    <p class="mb-0">{{ $app->description }}</p>
                </div>
                @endif
                
                @if(Auth::user()->isAdmin())
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('client-apps.show', $app) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                    <a href="{{ route('client-apps.edit', $app) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('client-apps.toggle-status', $app) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        @if($app->is_active)
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-ban"></i> Nonaktifkan
                        </button>
                        @else
                        <button type="submit" class="btn btn-sm btn-dark">
                            <i class="fas fa-check"></i> Aktifkan
                        </button>
                        @endif
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($clientApps->isEmpty())
<div class="text-center p-5">
    <p class="text-muted">Belum ada aplikasi yang terdaftar.</p>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var clipboard = new ClipboardJS('.copy-btn');
        
        clipboard.on('success', function(e) {
            const originalHTML = e.trigger.innerHTML;
            e.trigger.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(function() {
                e.trigger.innerHTML = originalHTML;
            }, 1500);
            e.clearSelection();
        });
    });
</script>
@endsection

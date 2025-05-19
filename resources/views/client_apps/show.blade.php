@extends('layouts.app')

@section('title', 'Detail Aplikasi - SSO BPS')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Detail Aplikasi: {{ $clientApp->name }}</h4>
                    <span class="badge {{ $clientApp->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $clientApp->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> Client ID dan Client Secret adalah kredensial rahasia. Jangan berikan kepada pihak yang tidak berwenang.
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted">Client ID</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $clientApp->client_id }}" readonly>
                        <button class="btn btn-outline-secondary copy-btn" data-clipboard-text="{{ $clientApp->client_id }}" type="button">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted">Client Secret</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $clientApp->client_secret }}" readonly>
                        <button class="btn btn-outline-secondary copy-btn" data-clipboard-text="{{ $clientApp->client_secret }}" type="button">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <div class="mt-2">
                        <form action="{{ route('client-apps.regenerate-secret', $clientApp) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Apakah Anda yakin ingin regenerate Client Secret? Aplikasi yang sudah terintegrasi harus diupdate.')">
                                <i class="fas fa-sync-alt"></i> Regenerate Secret
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted">Redirect URI</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="{{ $clientApp->redirect_uri }}" readonly>
                        <button class="btn btn-outline-secondary copy-btn" data-clipboard-text="{{ $clientApp->redirect_uri }}" type="button">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>
                
                @if($clientApp->description)
                <div class="mb-4">
                    <label class="form-label text-muted">Deskripsi</label>
                    <p>{{ $clientApp->description }}</p>
                </div>
                @endif
                
                <div class="mb-4">
                    <label class="form-label text-muted">Dibuat Oleh</label>
                    <p>{{ $clientApp->creator ? $clientApp->creator->name : 'Unknown' }}</p>
                </div>
                
                <div class="mb-4">
                    <label class="form-label text-muted">Dibuat Pada</label>
                    <p>{{ $clientApp->created_at->format('d M Y H:i') }}</p>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('client-apps.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('client-apps.edit', $clientApp) }}" class="btn btn-success">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('client-apps.destroy', $clientApp) }}" method="POST" class="d-inline ms-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus aplikasi ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var clipboard = new ClipboardJS('.copy-btn');
        
        clipboard.on('success', function(e) {
            const originalHTML = e.trigger.innerHTML;
            e.trigger.innerHTML = '<i class="fas fa-check"></i> Copied!';
            setTimeout(function() {
                e.trigger.innerHTML = originalHTML;
            }, 1500);
            e.clearSelection();
        });
    });
</script>
@endsection

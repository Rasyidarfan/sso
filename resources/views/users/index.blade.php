@extends('layouts.app')

@section('title', 'Daftar Akun - SSO BPS')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Daftar Akun</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Akun
    </a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Tim</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nip9 }}<br>{{ $user->nip16 }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="role-tag">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    @if($user->is_active)
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-dark">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

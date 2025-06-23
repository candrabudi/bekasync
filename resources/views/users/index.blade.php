@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-xl-6">
                            <div class="page-title-content">
                                <h3>Data Pengguna</h3>
                                <p class="mb-2 text-muted">Daftar Superadmin, Walikota, dan Wakil Walikota</p>
                            </div>
                        </div>
                        <div class="col-xl-auto">
                            <a href="{{ url('users/create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Pengguna
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <div class="row mt-3">
            <div class="col-xxl-12">
                @include('layouts.partials.menu-setting')

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Username</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>No. Telepon</th>
                                        <th>Role</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td><strong>{{ $user->username }}</strong></td>
                                            <td>{{ $user->detail->full_name ?? '-' }}</td>
                                            <td>{{ $user->detail->email ?? '-' }}</td>
                                            <td>{{ $user->detail->phone_number ?? '-' }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Yakin hapus?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <em>Data pengguna belum tersedia.</em>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($users->hasPages())
                            <nav class="mt-4 d-flex justify-content-end">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $users->previousPageUrl() }}">&laquo;</a>
                                    </li>
                                    @for ($i = max($users->currentPage() - 2, 1); $i <= min($users->currentPage() + 2, $users->lastPage()); $i++)
                                        <li class="page-item {{ $users->currentPage() === $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $users->nextPageUrl() }}">&raquo;</a>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

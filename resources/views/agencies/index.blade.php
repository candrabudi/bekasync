@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-xl-4">
                            <div class="page-title-content">
                                <h3>Organisasi Perangkat Daerah</h3>
                                <p class="mb-2 text-muted">Data List Organisasi Perangkat Daerah</p>
                            </div>
                        </div>
                        <div class="col-xl-auto">
                            <a href="{{ url('agencies/create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Instansi
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
                                        <th>OPD</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($agencies as $agency)
                                        <tr>
                                            <td><strong>{{ $agency->username }}</strong></td>
                                            <td>{{ $agency->detail->full_name ?? '-' }}</td>
                                            <td>{{ $agency->detail->email ?? '-' }}</td>
                                            <td>{{ $agency->detail->phone_number ?? '-' }}</td>
                                            <td>{{ $agency->detail->governmentUnit->name ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('agencies/' . $agency->id . '/edit') }}"
                                                    class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                                <form action="{{ url('agencies/' . $agency->id . '/delete') }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Yakin hapus?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <em>Data instansi belum tersedia.</em>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($agencies->hasPages())
                            <nav class="mt-4 d-flex justify-content-end">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item {{ $agencies->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $agencies->previousPageUrl() }}">&laquo;</a>
                                    </li>
                                    @for ($i = max($agencies->currentPage() - 2, 1); $i <= min($agencies->currentPage() + 2, $agencies->lastPage()); $i++)
                                        <li class="page-item {{ $agencies->currentPage() === $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $agencies->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ !$agencies->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $agencies->nextPageUrl() }}">&raquo;</a>
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

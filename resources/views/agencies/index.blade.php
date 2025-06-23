@extends('layouts.app')

@section('content')
    <div class="container py-4"> {{-- Tambah padding atas dan bawah --}}
        <div class="row mb-4 align-items-center"> {{-- Margin bawah lebih besar dan rata tengah vertikal --}}
            <div class="col-md-8"> {{-- Lebihkan ruang untuk judul --}}
                <div class="page-title-content">
                    <h1 class="display-5 fw-bold text-dark mb-1">Organisasi Perangkat Daerah</h1> {{-- Ukuran lebih besar, bold, warna gelap, margin bawah sedikit --}}
                    <p class="lead text-muted">Kelola data instansi pemerintah dengan mudah dan efisien.</p> {{-- Deskripsi lebih menarik --}}
                </div>
            </div>
            <div class="col-md-4 text-end"> {{-- Posisikan tombol di kanan --}}
                <a href="{{ url('agencies/create') }}" class="btn btn-primary btn-lg shadow-sm"> {{-- Tombol lebih besar, dengan shadow --}}
                    <i class="bi bi-plus-circle me-2"></i> Tambah Instansi Baru {{-- Ikon lebih menonjol dan teks lebih jelas --}}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert"> {{-- Alert lebih interaktif --}}
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mt-4"> {{-- Margin atas lebih besar --}}
            <div class="col-xxl-12">
                {{-- Ini asumsi menu-setting Anda juga sudah powerfull atau akan disesuaikan --}}
                @include('layouts.partials.menu-setting')

                <div class="card shadow-lg border-0 rounded-3"> {{-- Card dengan shadow lebih kuat, border hilang, dan sudut membulat --}}
                    <div class="card-body p-5"> {{-- Padding lebih besar di dalam card --}}
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-borderless align-middle mb-0"> {{-- Tanpa border, striped untuk keterbacaan --}}
                                <thead class="bg-light sticky-top"> {{-- Header tabel tetap di atas saat scroll --}}
                                    <tr>
                                        <th class="py-3 text-uppercase text-muted">Username</th> {{-- Padding lebih besar, uppercase, muted --}}
                                        <th class="py-3 text-uppercase text-muted">Nama Lengkap</th>
                                        <th class="py-3 text-uppercase text-muted">Email</th>
                                        <th class="py-3 text-uppercase text-muted">No. Telepon</th>
                                        <th class="py-3 text-uppercase text-muted">OPD</th>
                                        <th class="text-center py-3 text-uppercase text-muted">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($agencies as $agency)
                                        <tr class="border-bottom"> {{-- Tambah border bawah untuk setiap baris --}}
                                            <td class="py-3"><strong>{{ $agency->username }}</strong></td> {{-- Padding lebih besar --}}
                                            <td class="py-3">{{ $agency->detail->full_name ?? '-' }}</td>
                                            <td class="py-3">{{ $agency->detail->email ?? '-' }}</td>
                                            <td class="py-3">{{ $agency->detail->phone_number ?? '-' }}</td>
                                            <td class="py-3">{{ $agency->detail->governmentUnit->name ?? '-' }}</td>
                                            <td class="text-center py-3">
                                                <a href="{{ url('agencies/' . $agency->id . '/edit') }}"
                                                    class="btn btn-outline-primary btn-sm me-2 rounded-pill"> {{-- Tombol edit lebih kecil, rounded --}}
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ url('agencies/' . $agency->id . '/delete') }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill" {{-- Tombol delete lebih kecil, rounded --}}
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus instansi ini? Tindakan ini tidak dapat dibatalkan.')"> {{-- Konfirmasi lebih jelas --}}
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5"> {{-- Padding lebih besar untuk pesan kosong --}}
                                                <i class="bi bi-info-circle display-4 mb-3 d-block"></i> {{-- Ikon besar untuk visual --}}
                                                <p class="fs-5"><em>Belum ada data instansi yang tersedia.</em></p> {{-- Pesan lebih menonjol --}}
                                                <p class="text-muted">Klik tombol "Tambah Instansi Baru" untuk memulai.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($agencies->hasPages())
                            <nav class="mt-4 d-flex justify-content-center justify-content-md-end"> {{-- Rata tengah di mobile, kanan di desktop --}}
                                <ul class="pagination pagination-sm shadow-sm rounded-pill overflow-hidden"> {{-- Pagination dengan shadow dan rounded --}}
                                    <li class="page-item {{ $agencies->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $agencies->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    @for ($i = max($agencies->currentPage() - 2, 1); $i <= min($agencies->currentPage() + 2, $agencies->lastPage()); $i++)
                                        <li class="page-item {{ $agencies->currentPage() === $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $agencies->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ !$agencies->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $agencies->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
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
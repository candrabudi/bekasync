@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow rounded-4">
        <div class="card-body p-5">

            {{-- Informasi Tiket --}}
            <div class="mb-4 border-bottom pb-3">
                <h4 class="fw-bold text-primary">Tiket: {{ $incident->ticket }}</h4>
                <small class="text-muted">
                    Dibuat:
                    {{ $incident->incident_created_at ? \Carbon\Carbon::parse($incident->incident_created_at)->diffForHumans() : '-' }}
                    ({{ $incident->incident_created_at ? \Carbon\Carbon::parse($incident->incident_created_at)->format('d-m-Y H:i') : '-' }})
                </small>
                <br>
                <span class="badge bg-{{ statusColor($incident->status) }} rounded-pill mt-2">
                    <i class="ri-{{ statusIcon($incident->status) }} me-1"></i>{{ statusLabel($incident->status) }}
                </span>
            </div>

            {{-- Informasi Penelepon --}}
            <div class="mb-5">
                <h5 class="text-muted fw-semibold mb-3"><i class="ri-phone-line me-2"></i>Informasi Penelepon</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama:</strong> {{ $incident->caller ?? '-' }}</p>
                        <p><strong>Telepon:</strong> {{ $incident->phone ?? '-' }}</p>
                        <p><strong>Nomor Tidak Dimasker:</strong> {{ $incident->phone_unmask ?? '-' }}</p>
                        <p><strong>Nomor VOIP:</strong> {{ $incident->voip_number ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Saluran (Channel):</strong> {{ $incident->channel_id ?? '-' }}</p>
                        <p><strong>Tipe Panggilan:</strong> {{ $incident->call_type ?? '-' }}</p>
                        <p><strong>Dibuat Oleh:</strong> {{ $incident->created_by ?? '-' }}</p>
                        <p><strong>Caller ID:</strong> {{ $incident->caller_id ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Lokasi --}}
            <div class="mb-5">
                <h5 class="text-muted fw-semibold mb-3"><i class="ri-map-pin-line me-2"></i>Lokasi Kejadian</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Alamat:</strong> {{ $incident->address ?? '-' }}</p>
                        <p><strong>Lokasi Spesifik:</strong>
                            @if ($incident->location)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($incident->location) }}"
                                   target="_blank" class="text-decoration-none text-primary">
                                    <i class="ri-map-pin-2-line me-1"></i>{{ $incident->location }}
                                </a>
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Kecamatan:</strong> {{ $incident->district ?? '-' }}</p>
                        <p><strong>Kelurahan:</strong> {{ $incident->subdistrict ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>ID Kecamatan:</strong> {{ $incident->district_id ?? '-' }}</p>
                        <p><strong>ID Kelurahan:</strong> {{ $incident->subdistrict_id ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Kronologi --}}
            <div class="mb-5">
                <h5 class="text-muted fw-semibold mb-3"><i class="ri-information-line me-2"></i>Keterangan & Kronologi</h5>
                <p><strong>Catatan Tambahan:</strong><br>{{ $incident->notes ?? '-' }}</p>
                <p><strong>Deskripsi Lengkap:</strong><br>{{ $incident->description ?? '-' }}</p>
            </div>

            {{-- Respons Instansi --}}
            <div class="mb-5">
                <h5 class="text-muted fw-semibold mb-3"><i class="ri-shield-user-line me-2"></i>Respons Instansi</h5>
                @if ($agencyResponses->count())
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Instansi</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($agencyResponses as $res)
                                <tr>
                                    <td>{{ $res->dinas ?? '-' }}</td>
                                    <td>{{ statusLabel($res->status) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($res->created_at)->format('d-m-Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">Belum ada respons dari instansi.</p>
                @endif
            </div>

            {{-- Riwayat Log --}}
            <div class="mb-5">
                <h5 class="text-muted fw-semibold mb-3"><i class="ri-history-line me-2"></i>Riwayat Penanganan</h5>
                @if ($logs->count())
                    <ul class="list-group list-group-flush">
                        @foreach ($logs as $log)
                            <li class="list-group-item">
                                <strong>Status:</strong> {{ statusLabel($log->status) }}<br>
                                <strong>Oleh:</strong> {{ $log->created_by_name ?? 'Sistem' }}<br>
                                <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i') }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Belum ada log tersedia.</p>
                @endif
            </div>

            {{-- Metadata --}}
            <div class="mb-4">
                <h5 class="text-muted fw-semibold mb-3"><i class="ri-database-2-line me-2"></i>Metadata</h5>
                <p><strong>ID:</strong> {{ $incident->id }}</p>
                <p><strong>Dibuat pada:</strong> {{ \Carbon\Carbon::parse($incident->created_at)->format('d-m-Y H:i') }}</p>
                <p><strong>Update terakhir:</strong> {{ \Carbon\Carbon::parse($incident->updated_at)->format('d-m-Y H:i') }}</p>
            </div>

            <div class="text-end">
                <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-arrow-left-line me-1"></i> Kembali ke Daftar
                </a>
            </div>

        </div>
    </div>
</div>
@endsection

@php
    use Carbon\Carbon;

    function statusLabel($status) {
        return match($status) {
            1 => 'Terbuka',
            2 => 'Dalam Proses',
            3 => 'Selesai',
            4 => 'Tertunda',
            default => 'Tidak Diketahui',
        };
    }

    function statusColor($status) {
        return match($status) {
            1 => 'primary',
            2 => 'warning',
            3 => 'success',
            4 => 'secondary',
            default => 'light',
        };
    }

    function statusIcon($status) {
        return match($status) {
            1 => 'folder-open-line',
            2 => 'settings-line',
            3 => 'check-double-line',
            4 => 'time-line',
            default => 'question-line',
        };
    }
@endphp

@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="card shadow-sm rounded-4">
            <div class="card-body">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="mb-1 fw-bold text-primary">Ticket: {{ $incident->ticket }}</h4>
                        <small class="text-muted">Created at: {{ $incident->incident_created_at }}</small>
                    </div>
                    <span class="badge bg-{{ statusColor($incident->status) }}">
                        {{ statusLabel($incident->status) }}
                    </span>
                </div>

                <hr>

                {{-- Info Sections --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold">📞 Caller Information</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Name:</strong> {{ $incident->caller ?? '-' }}</li>
                            <li><strong>Phone:</strong> {{ $incident->phone ?? '-' }}</li>
                            <li><strong>Unmasked:</strong> {{ $incident->phone_unmask ?? '-' }}</li>
                            <li><strong>VOIP Number:</strong> {{ $incident->voip_number ?? '-' }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold">📋 Call Details</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Category:</strong> {{ $incident->category ?? '-' }}</li>
                            <li><strong>Call Type:</strong> {{ $incident->call_type ?? '-' }}</li>
                            <li><strong>Channel ID:</strong> {{ $incident->channel_id ?? '-' }}</li>
                            <li><strong>Created By:</strong> {{ $incident->created_by ?? '-' }}</li>
                        </ul>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold">📍 Location</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Address:</strong> {{ $incident->address ?? '-' }}</li>
                            <li><strong>Location:</strong> {{ $incident->location ?? '-' }}</li>
                            <li><strong>District:</strong> {{ $incident->district ?? '-' }}</li>
                            <li><strong>Subdistrict:</strong> {{ $incident->subdistrict ?? '-' }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold">🧾 Other Info</h6>
                        <ul class="list-unstyled small">
                            <li><strong>Status:</strong> {{ statusLabel($incident->status) }}</li>
                            <li><strong>Notes:</strong> {{ $incident->notes ?? '-' }}</li>
                            <li><strong>Description:</strong> {{ $incident->description ?? '-' }}</li>
                            <li><strong>Updated At:</strong> {{ $incident->incident_updated_at ?? '-' }}</li>
                        </ul>
                    </div>
                </div>

                <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary">← Back to List</a>
            </div>
        </div>
    </div>
@endsection

@php
    function statusLabel($status)
    {
        return match($status) {
            1 => 'Open',
            2 => 'In Progress',
            3 => 'Closed',
            4 => 'Pending',
            default => 'Unknown',
        };
    }

    function statusColor($status)
    {
        return match($status) {
            1 => 'primary',
            2 => 'warning',
            3 => 'success',
            4 => 'secondary',
            default => 'light',
        };
    }
@endphp

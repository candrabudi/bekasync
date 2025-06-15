@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Incident List</h2>

        {{-- FILTERS --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <input type="text" id="search" class="form-control" placeholder="Search ticket/caller/phone">
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="start_date" class="form-control" placeholder="Start date">
                    </div>
                    <div class="col-md-2">
                        <input type="text" id="end_date" class="form-control" placeholder="End date">
                    </div>
                    <div class="col-md-2">
                        <select id="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="1">Open</option>
                            <option value="2">In Progress</option>
                            <option value="3">Closed</option>
                            <option value="4">Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3 mt-2">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary w-100" onclick="fetchData()">Filter</button>
                            <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">Reset</button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select id="district" class="form-select">
                            <option value="">All Districts</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district }}">{{ $district }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="subdistrict" class="form-select">
                            <option value="">All Subdistricts</option>
                            @foreach ($subdistricts as $subdistrict)
                                <option value="{{ $subdistrict }}">{{ $subdistrict }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- LOADING --}}
        <div id="loading" class="text-center my-4" style="display: none;">
            <div class="spinner-border text-primary" role="status"></div>
        </div>

        {{-- CARDS --}}
        <div id="incidentCards" class="row g-3"></div>

        {{-- PAGINATION --}}
        <nav>
            <ul class="pagination justify-content-center mt-4" id="pagination"></ul>
        </nav>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#start_date", {
            dateFormat: "Y-m-d"
        });
        flatpickr("#end_date", {
            dateFormat: "Y-m-d"
        });

        let currentPage = 1;

        function fetchData(page = 1) {
            currentPage = page;

            const params = {
                page,
                search: document.getElementById('search').value,
                start_date: document.getElementById('start_date').value,
                end_date: document.getElementById('end_date').value,
                category: document.getElementById('category').value,
                status: document.getElementById('status').value,
                district: document.getElementById('district').value,
                subdistrict: document.getElementById('subdistrict').value,
            };

            document.getElementById('loading').style.display = 'block';
            document.getElementById('incidentCards').innerHTML = '';

            axios.get('/incidents/data', {
                    params
                })
                .then(response => {
                    const data = response.data;
                    const cards = data.data.map(item => `
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100 border-0">
                                <div class="card-body">
                                    <h5 class="fw-bold text-primary">${item.ticket}</h5>
                                    <span class="badge bg-${getStatusColor(item.status)} mb-2">${getStatusLabel(item.status)}</span>
                                    <p class="mb-1"><strong>Caller:</strong> ${item.caller ?? '-'}</p>
                                    <p class="mb-1"><strong>Phone:</strong> ${item.phone ?? '-'}</p>
                                    <p class="mb-1"><strong>Category:</strong> ${item.category ?? '-'}</p>
                                    <p class="mb-1"><strong>Location:</strong> ${item.location ?? '-'}</p>
                                    <p class="small text-muted">${item.district ?? ''} ${item.subdistrict ? '- ' + item.subdistrict : ''}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">${item.incident_created_at}</small>
                                        <a href="/incidents/${item.id}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    document.getElementById('incidentCards').innerHTML = cards ||
                        `<div class="text-center">No incidents found.</div>`;
                    renderPagination(data);
                })
                .catch(() => {
                    document.getElementById('incidentCards').innerHTML =
                        `<div class="text-danger text-center">Error loading data.</div>`;
                })
                .finally(() => {
                    document.getElementById('loading').style.display = 'none';
                });
        }

        function resetFilter() {
            ['search', 'start_date', 'end_date', 'category', 'status', 'district', 'subdistrict'].forEach(id => {
                document.getElementById(id).value = '';
            });
            fetchData(1);
        }

        function renderPagination(data) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const total = data.last_page;
            const current = data.current_page;
            const range = 1;
            const pages = [];

            if (current > 1) {
                pages.push(
                    `<li class="page-item"><button class="page-link" onclick="fetchData(${current - 1})">&laquo;</button></li>`
                    );
            }

            for (let i = 1; i <= total; i++) {
                if (i === 1 || i === total || (i >= current - range && i <= current + range)) {
                    pages.push(`
                        <li class="page-item ${i === current ? 'active' : ''}">
                            <button class="page-link" onclick="fetchData(${i})">${i}</button>
                        </li>
                    `);
                } else if (i === current - range - 1 || i === current + range + 1) {
                    pages.push(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
            }

            if (current < total) {
                pages.push(
                    `<li class="page-item"><button class="page-link" onclick="fetchData(${current + 1})">&raquo;</button></li>`
                    );
            }

            pagination.innerHTML = pages.join('');
        }

        function getStatusLabel(status) {
            const map = {
                1: 'Open',
                2: 'In Progress',
                3: 'Closed',
                4: 'Pending'
            };
            return map[status] || 'Unknown';
        }

        function getStatusColor(status) {
            const map = {
                1: 'primary',
                2: 'warning',
                3: 'success',
                4: 'secondary'
            };
            return map[status] || 'light';
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchData();
        });
    </script>
@endpush

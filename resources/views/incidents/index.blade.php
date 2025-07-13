@extends('layouts.app')

@section('content')
    <div class="container">
        <style>
            .bg-gradient-green {
                background: linear-gradient(90deg, #28a745, #218838) !important;
                color: white !important;
                transition: all 0.3s ease-in-out;
            }

            .nav-link {
                border-radius: 0.5rem !important;
            }

            .nav-link:hover {
                background-color: #e6f4ea !important;
                color: #218838 !important;
                transition: all 0.2s ease-in-out;
            }
        </style>
        <ul class="nav nav-tabs nav-pills bg-white p-2 rounded shadow-sm mb-4 gap-2" style="overflow-x: auto;">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-1 fw-semibold {{ request()->is('dashboard') ? 'active bg-gradient-primary text-white shadow-sm' : 'text-secondary' }}"
                    href="">
                    <i class="bi bi-bar-chart-steps"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center gap-1 fw-semibold {{ request()->is('incidents*') ? 'active bg-gradient-primary text-white shadow-sm' : 'text-secondary' }}"
                    href="{{ route('incidents.index') }}">
                    <i class="bi bi-list-task"></i> List Incident
                </a>
            </li>
        </ul>

        <h2 class="mb-4">Daftar Insiden</h2>

        {{-- FILTER --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <input type="text" id="search" class="form-control"
                            placeholder="Cari tiket/penelepon/telepon">
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="daterange" class="form-control" placeholder="Rentang tanggal">
                    </div>
                    <div class="col-md-2">
                        <select id="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1">Terbuka</option>
                            <option value="2">Dalam Proses</option>
                            <option value="3">Selesai</option>
                            <option value="4">Tertunda</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="district" class="form-select">
                            <option value="">Semua Kecamatan</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district }}">{{ $district }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="subdistrict" class="form-select">
                            <option value="">Semua Kelurahan</option>
                            @foreach ($subdistricts as $subdistrict)
                                <option value="{{ $subdistrict }}">{{ $subdistrict }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary w-100" onclick="fetchData()">Filter</button>
                            <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- LOADING --}}
        <div id="loading" class="text-center my-4" style="display: none;">
            <div class="spinner-border text-primary" role="status"></div>
        </div>

        {{-- KARTU / SKELETON --}}
        <div id="incidentCards" class="row g-3"></div>

        {{-- PAGINASI --}}
        <nav>
            <ul class="pagination justify-content-center mt-4" id="pagination"></ul>
        </nav>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">

    <style>
        .skeleton {
            background: #e2e2e2;
            border-radius: 6px;
            position: relative;
            overflow: hidden;
        }

        .skeleton::after {
            content: "";
            position: absolute;
            top: 0;
            left: -150px;
            height: 100%;
            width: 150px;
            background: linear-gradient(to right, transparent 0%, #f5f5f5 50%, transparent 100%);
            animation: loading 1.2s infinite;
        }

        @keyframes loading {
            0% {
                left: -150px;
            }

            100% {
                left: 100%;
            }
        }

        .skeleton-line {
            height: 12px;
            margin-bottom: 8px;
        }

        .skeleton-title {
            width: 60%;
            height: 16px;
        }

        .skeleton-badge {
            width: 30%;
            height: 14px;
            margin-bottom: 10px;
        }

        .skeleton-btn {
            width: 70px;
            height: 28px;
            margin-top: 10px;
            border-radius: 6px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        let startDate = '';
        let endDate = '';

        $('#daterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });

        $('#daterange').on('apply.daterangepicker', function(ev, picker) {
            startDate = picker.startDate.format('YYYY-MM-DD');
            endDate = picker.endDate.format('YYYY-MM-DD');
            $(this).val(startDate + ' - ' + endDate);
        });

        $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            startDate = '';
            endDate = '';
        });

        let currentPage = 1;

        function fetchData(page = 1) {
            currentPage = page;

            const params = {
                page,
                search: document.getElementById('search').value,
                start_date: startDate,
                end_date: endDate,
                category: document.getElementById('category').value,
                status: document.getElementById('status').value,
                district: document.getElementById('district').value,
                subdistrict: document.getElementById('subdistrict').value,
            };

            document.getElementById('loading').style.display = 'block';
            document.getElementById('incidentCards').innerHTML = generateSkeletonCards(6);

            axios.get('/incidents/data', {
                    params
                })
                .then(response => {
                    const data = response.data;

                    function formatTanggalIndonesia(datetime) {
                        const options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                        };
                        return new Date(datetime).toLocaleDateString('id-ID', options);
                    }

                    const cards = data.data.map(item => `
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100 border-0">
                                <div class="card-body">
                                    <h5 class="fw-bold text-primary">${item.ticket}</h5>
                                    <span class="badge bg-${getStatusColor(item.status)} mb-2">${getStatusLabel(item.status)}</span>
                                    <p class="mb-1"><strong>Penelepon:</strong> ${item.caller ?? '-'}</p>
                                    <p class="mb-1"><strong>Telepon:</strong> ${item.phone ?? '-'}</p>
                                    <p class="mb-1"><strong>Kategori:</strong> ${item.category ?? '-'}</p>
                                    <p class="mb-1"><strong>Lokasi:</strong> ${item.location ?? '-'}</p>
                                    <p class="small text-muted">${item.district ?? ''} ${item.subdistrict ? '- ' + item.subdistrict : ''}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                       <small class="text-muted">${formatTanggalIndonesia(item.created_at)}</small>
                                       <a href="/incidents/${item.id}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    document.getElementById('incidentCards').innerHTML = cards ||
                        `<div class="text-center">Tidak ada insiden ditemukan.</div>`;
                    renderPagination(data);
                })
                .catch(() => {
                    document.getElementById('incidentCards').innerHTML =
                        `<div class="text-danger text-center">Gagal memuat data.</div>`;
                })
                .finally(() => {
                    setTimeout(() => {
                        document.getElementById('loading').style.display = 'none';
                    }, 300);
                });
        }

        function resetFilter() {
            ['search', 'category', 'status', 'district', 'subdistrict'].forEach(id => {
                document.getElementById(id).value = '';
            });
            $('#daterange').val('');
            startDate = '';
            endDate = '';
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
                    pages.push(`<li class="page-item ${i === current ? 'active' : ''}">
                        <button class="page-link" onclick="fetchData(${i})">${i}</button>
                    </li>`);
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
                1: 'Terbuka',
                2: 'Dalam Proses',
                3: 'Selesai',
                4: 'Tertunda'
            };
            return map[status] || 'Tidak Diketahui';
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

        function generateSkeletonCards(count) {
            let html = '';
            for (let i = 0; i < count; i++) {
                html += `
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 border-0 p-3">
                        <div class="skeleton skeleton-title mb-2"></div>
                        <div class="skeleton skeleton-badge mb-3"></div>
                        <div class="skeleton skeleton-line" style="width: 80%"></div>
                        <div class="skeleton skeleton-line" style="width: 60%"></div>
                        <div class="skeleton skeleton-line" style="width: 70%"></div>
                        <div class="skeleton skeleton-line" style="width: 90%"></div>
                        <div class="skeleton skeleton-line" style="width: 40%"></div>
                        <div class="d-flex justify-content-between mt-3">
                            <div class="skeleton" style="width: 40%; height: 10px;"></div>
                            <div class="skeleton skeleton-btn"></div>
                        </div>
                    </div>
                </div>`;
            }
            return html;
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchData();
        });
    </script>
@endpush

@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Insiden berdasarkan Kecamatan {{ $district }}</h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-3">
                    <input type="text" id="search" class="form-control" placeholder="Cari tiket/penelepon/telepon">
                </div>
                <div class="col-md-4 col-lg-2">
                    <input type="text" id="start_date" class="form-control" placeholder="Tanggal mulai">
                </div>
                <div class="col-md-4 col-lg-2">
                    <input type="text" id="end_date" class="form-control" placeholder="Tanggal berakhir">
                </div>
                <div class="col-md-4 col-lg-2">
                    <select id="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-2">
                    <select id="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1">Open</option>
                        <option value="2">In Progress</option>
                        <option value="3">Closed</option>
                        <option value="4">Pending</option>
                    </select>
                </div>
                <div class="col-md-4 col-lg-2">
                    <select id="district" class="form-select">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district }}">{{ $district }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-2">
                    <select id="subdistrict" class="form-select">
                        <option value="">Semua Kelurahan</option>
                        @foreach ($subdistricts as $subdistrict)
                            <option value="{{ $subdistrict }}">{{ $subdistrict }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-4 col-lg-3 mt-2">
                    <div class="d-grid gap-2 d-md-flex">
                        <button class="btn btn-primary flex-fill" onclick="fetchData()">Filter</button>
                        <button class="btn btn-outline-secondary flex-fill" onclick="resetFilter()">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loading" class="text-center my-4" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Memuat...</span>
        </div>
    </div>

    <div id="incidentCards" class="row g-3"></div>

    <nav aria-label="Navigasi Halaman">
        <ul class="pagination justify-content-center mt-4" id="pagination"></ul>
    </nav>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script>
    const today = new Date().toISOString().split('T')[0];
    flatpickr("#start_date", {
        dateFormat: "Y-m-d",
        defaultDate: today
    });
    flatpickr("#end_date", {
        dateFormat: "Y-m-d",
        defaultDate: today
    });

    let currentPage = 1;
    const dinasParam = window.location.pathname.split('/')[3]; 
    const getEl = (id) => document.getElementById(id);

    function fetchData(page = 1) {
        currentPage = page;
        const params = {
            page,
            search: getEl('search').value,
            start_date: getEl('start_date').value,
            end_date: getEl('end_date').value,
            category: getEl('category').value,
            status: getEl('status').value,
            district: getEl('district').value,
            subdistrict: getEl('subdistrict').value,
        };

        getEl('loading').style.display = 'block';
        getEl('incidentCards').innerHTML = '';

        axios.get(`/incident/by-district/${dinasParam}/list`, { params })
            .then(response => {
                const data = response.data;
                const cards = data.data.length > 0 ?
                    data.data.map(item => `
                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100 border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold text-primary">${item.ticket}</h5>
                                    <span class="badge bg-${getStatusColor(item.status)} mb-2 align-self-start">${getStatusLabel(item.status)}</span>
                                    <p class="mb-1"><strong>Penelepon:</strong> ${item.caller ?? '-'}</p>
                                    <p class="mb-1"><strong>Telepon:</strong> ${item.phone ?? '-'}</p>
                                    <p class="mb-1"><strong>Kategori:</strong> ${item.category ?? '-'}</p>
                                    <p class="mb-1"><strong>Lokasi:</strong> ${item.location ?? '-'}</p>
                                    <p class="small text-muted mb-auto">${item.district ?? ''} ${item.subdistrict ? '- ' + item.subdistrict : ''}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">${item.incident_created_at}</small>
                                        <a href="/incidents/${item.id}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('') :
                    `<div class="col-12 text-center py-4">
                        <p class="lead text-muted">Tidak ada insiden yang ditemukan.</p>
                    </div>`;

                getEl('incidentCards').innerHTML = cards;
                renderPagination(data);
            })
            .catch(error => {
                console.error('Error loading data:', error);
                getEl('incidentCards').innerHTML = `
                    <div class="col-12 text-danger text-center py-4">
                        <p class="lead">Terjadi kesalahan saat memuat data. Silakan coba lagi.</p>
                    </div>`;
            })
            .finally(() => {
                getEl('loading').style.display = 'none';
            });
    }

    function resetFilter() {
        ['search', 'start_date', 'end_date', 'category', 'status', 'district', 'subdistrict', 'call_type', 'channel_id'].forEach(id => {
            getEl(id).value = '';
        });
        getEl('start_date')._flatpickr.setDate(today);
        getEl('end_date')._flatpickr.setDate(today);
        fetchData(1);
    }

    function renderPagination(data) {
        const pagination = getEl('pagination');
        pagination.innerHTML = '';
        const { last_page: totalPages, current_page: currentPage } = data;
        const maxPagesToShow = 5;

        let startPage, endPage;
        if (totalPages <= maxPagesToShow) {
            startPage = 1;
            endPage = totalPages;
        } else {
            if (currentPage <= Math.ceil(maxPagesToShow / 2)) {
                startPage = 1;
                endPage = maxPagesToShow;
            } else if (currentPage + Math.floor(maxPagesToShow / 2) >= totalPages) {
                startPage = totalPages - maxPagesToShow + 1;
                endPage = totalPages;
            } else {
                startPage = currentPage - Math.floor(maxPagesToShow / 2);
                endPage = currentPage + Math.ceil(maxPagesToShow / 2) - 1;
            }
        }

        if (currentPage > 1) {
            pagination.innerHTML += `<li class="page-item"><button class="page-link" onclick="fetchData(${currentPage - 1})" aria-label="Previous"><span aria-hidden="true">&laquo;</span></button></li>`;
        }

        for (let i = startPage; i <= endPage; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <button class="page-link" onclick="fetchData(${i})">${i}</button>
                </li>`;
        }

        if (currentPage < totalPages) {
            pagination.innerHTML += `<li class="page-item"><button class="page-link" onclick="fetchData(${currentPage + 1})" aria-label="Next"><span aria-hidden="true">&raquo;</span></button></li>`;
        }
    }

    const statusMap = {
        1: { label: 'Open', color: 'primary' },
        2: { label: 'In Progress', color: 'warning' },
        3: { label: 'Closed', color: 'success' },
        4: { label: 'Pending', color: 'secondary' },
    };

    function getStatusLabel(status) {
        return statusMap[status]?.label || 'Unknown';
    }

    function getStatusColor(status) {
        return statusMap[status]?.color || 'light';
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchData();
    });
</script>
@endpush

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
                                <p class="mb-2">Data List Organisasi Perangkat Daerah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-12">
                @include('layouts.partials.menu-setting')

                <div class="card position-relative" id="card-container">
                    <div class="card-body position-relative" style="min-height: 300px;">
                        <div class="table-responsive table-icon">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Perangkat Daerah</th>
                                        <th>Total Pengguna</th>
                                        <th>Pengguna Aktif</th>
                                        <th>Pengguna Tidak Aktif</th>
                                    </tr>
                                </thead>
                                <tbody id="units-tbody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No data available.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <nav aria-label="Page navigation" class="mt-3 d-flex justify-content-end" id="pagination-container">
                        </nav>
                        <style>
                            @keyframes spin {
                                to {
                                    transform: rotate(360deg);
                                }
                            }

                            .spinner {
                                animation: spin 1s linear infinite;
                            }
                        </style>

                        <div id="loading-overlay"
                            style="
                                display: none;
                                position: absolute;
                                inset: 0;
                                background: rgba(0,0,0,0.25);
                                z-index: 999;
                                align-items: center;
                                justify-content: center;
                                border-radius: 1rem;
                            ">
                            <svg class="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                style="height: 48px; width: 48px; color:#2563eb;">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" style="opacity: 0.25;"></circle>
                                <path fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" style="opacity: 0.75;"></path>
                            </svg>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tbody = document.getElementById('units-tbody');
            const paginationContainer = document.getElementById('pagination-container');
            const loadingOverlay = document.getElementById('loading-overlay');
            const cardBody = document.querySelector('#card-container .card-body');

            let currentPage = 1;
            let lastPage = 1;

            function showLoading() {
                loadingOverlay.style.display = 'flex';
            }

            function hideLoading() {
                loadingOverlay.style.display = 'none';
            }

            function fetchUnits(page = 1) {
                showLoading();

                axios.get("{{ route('government_units.data') }}", {
                        params: {
                            page
                        }
                    })
                    .then(response => {
                        const data = response.data;
                        currentPage = data.current_page;
                        lastPage = data.last_page;

                        renderTable(data.data);
                        renderPagination();
                    })
                    .catch(() => {
                        tbody.innerHTML = `
                            <tr><td colspan="4" class="text-center text-danger py-3">Failed to load data</td></tr>
                        `;
                        paginationContainer.innerHTML = '';
                    })
                    .finally(() => {
                        hideLoading();
                    });
            }

            function renderTable(units) {
                if (units.length === 0) {
                    tbody.innerHTML =
                        `<tr><td colspan="4" class="text-center text-muted">No data available.</td></tr>`;
                    return;
                }

                tbody.innerHTML = units.map(unit => `
                    <tr>
                        <td>${unit.name}</td>
                        <td>${unit.user_details_count}</td>
                        <td class="text-success fw-bold">${unit.active_users}</td>
                        <td class="text-danger fw-bold">${unit.inactive_users}</td>
                    </tr>
                `).join('');
            }

            function renderPagination() {
                if (lastPage <= 1) {
                    paginationContainer.innerHTML = '';
                    return;
                }

                function createButton(page, label = page, disabled = false, active = false) {
                    return `<button 
                        class="btn btn-outline-primary btn-xs mx-1 ${active ? 'active' : ''}" 
                        ${disabled ? 'disabled' : ''} data-page="${page}"
                        style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                        ${label}
                    </button>`;
                }

                let buttons = [];

                buttons.push(createButton(currentPage - 1, '«', currentPage === 1));

                if (lastPage <= 5) {
                    for (let i = 1; i <= lastPage; i++) {
                        buttons.push(createButton(i, i, false, i === currentPage));
                    }
                } else {
                    buttons.push(createButton(1, '1', false, currentPage === 1));

                    if (currentPage > 3) {
                        buttons.push(
                            `<span class="btn btn-xs disabled mx-1" style="font-size: 0.7rem;">...</span>`);
                    }

                    let start = Math.max(2, currentPage - 1);
                    let end = Math.min(lastPage - 1, currentPage + 1);

                    for (let i = start; i <= end; i++) {
                        buttons.push(createButton(i, i, false, i === currentPage));
                    }

                    if (currentPage < lastPage - 2) {
                        buttons.push(
                            `<span class="btn btn-xs disabled mx-1" style="font-size: 0.7rem;">...</span>`);
                    }

                    buttons.push(createButton(lastPage, lastPage, false, currentPage === lastPage));
                }

                buttons.push(createButton(currentPage + 1, '»', currentPage === lastPage));

                paginationContainer.innerHTML = buttons.join('');

                paginationContainer.querySelectorAll('button').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const page = Number(btn.getAttribute('data-page'));
                        if (!isNaN(page) && page !== currentPage) {
                            fetchUnits(page);
                            window.scrollTo({
                                top: cardBody.offsetTop - 20,
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            }

            fetchUnits();
        });
    </script>
@endsection

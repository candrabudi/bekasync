@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-xl-6">
                            <div class="page-title-content">
                                <h3>Data OPD</h3>
                                <p class="mb-2 text-muted">Daftar Organisasi Perangkat Daerah</p>
                            </div>
                        </div>
                        <div class="col-xl-auto">
                            <button onclick="showForm()" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Tambah OPD
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="overlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 d-none"
            onclick="hideForm()" style="z-index: 1040;"></div>

        <div class="row mt-3">
            <div class="col-xxl-12">
                @include('layouts.partials.menu-setting')

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Singkat</th>
                                        <th>Nama Lengkap</th>
                                        <th>Total Pengguna</th>
                                        <th>Aktif</th>
                                        <th>Tidak Aktif</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="units-tbody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Memuat data...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="pagination-container" class="d-flex justify-content-end mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side Form --}}
        <div id="side-form" class="position-fixed top-0 end-0 bg-white shadow p-4 d-none"
            style="width: 360px; height: 100vh; z-index: 1041;">
            <h5 id="form-title" class="mb-4">Tambah OPD</h5>
            <form id="unitForm">
                <input type="hidden" id="unit_id">
                <div class="mb-3">
                    <label class="form-label">Nama Singkat</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="long_name" required>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" onclick="hideForm()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Axios CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        let currentPage = 1;

        function showForm(unit = null) {
            document.getElementById('side-form').classList.remove('d-none');
            document.getElementById('overlay').classList.remove('d-none');
            document.getElementById('form-title').innerText = unit ? 'Edit OPD' : 'Tambah OPD';
            document.getElementById('unit_id').value = unit?.id || '';
            document.getElementById('name').value = unit?.name || '';
            document.getElementById('long_name').value = unit?.long_name || '';
        }

        function hideForm() {
            document.getElementById('side-form').classList.add('d-none');
            document.getElementById('overlay').classList.add('d-none');
            document.getElementById('unitForm').reset();
            document.getElementById('unit_id').value = '';
        }

        function fetchUnits(page = 1) {
            axios.get("{{ route('government_units.data') }}", {
                    params: {
                        page
                    }
                })
                .then(res => {
                    const units = res.data.data;
                    currentPage = res.data.current_page;
                    renderTable(units);
                    renderPagination(res.data.last_page);
                }).catch(() => {
                    document.getElementById('units-tbody').innerHTML =
                        `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data.</td></tr>`;
                });
        }

        function renderTable(units) {
            const tbody = document.getElementById('units-tbody');
            if (units.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data.</td></tr>`;
                return;
            }
            tbody.innerHTML = units.map(unit => `
            <tr>
                <td><strong>${unit.name}</strong></td>
                <td>${unit.long_name}</td>
                <td>${unit.user_details_count}</td>
                <td class="text-success">${unit.active_users}</td>
                <td class="text-danger">${unit.inactive_users}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary me-1" onclick='editUnit(${JSON.stringify(unit)})'>Edit</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUnit(${unit.id})">Hapus</button>
                </td>
            </tr>
        `).join('');
        }

        function renderPagination(lastPage) {
            const container = document.getElementById('pagination-container');
            if (lastPage <= 1) return container.innerHTML = '';
            let html = '';
            for (let i = 1; i <= lastPage; i++) {
                html +=
                    `<button class="btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'} me-1" onclick="fetchUnits(${i})">${i}</button>`;
            }
            container.innerHTML = html;
        }

        document.getElementById('unitForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('unit_id').value;
            const method = id ? 'put' : 'post';
            const url = id ? `/government-units/${id}` : `/government-units`;
            const data = {
                name: document.getElementById('name').value,
                long_name: document.getElementById('long_name').value
            };
            axios[method](url, data)
                .then(() => {
                    hideForm();
                    fetchUnits(currentPage);
                })
                .catch(() => alert("Gagal menyimpan data. Pastikan nama unik dan semua kolom terisi."));
        });

        function editUnit(unit) {
            showForm(unit);
        }

        function deleteUnit(id) {
            if (!confirm("Yakin ingin menghapus data ini?")) return;
            axios.delete(`/government-units/${id}`)
                .then(() => fetchUnits(currentPage))
                .catch(() => alert("Gagal menghapus data."));
        }

        document.addEventListener("DOMContentLoaded", () => fetchUnits());
    </script>
@endsection

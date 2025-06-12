<script>
    document.addEventListener("DOMContentLoaded", function() {
        const table = $('#dispatcherTable').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            info: false,
            language: {
                search: "Cari:",
                zeroRecords: "Data tidak ditemukan",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            },
            columnDefs: [{
                    targets: 4,
                    orderable: false
                }
            ]
        });

        function loadDispatcherData() {
            document.getElementById("loading").classList.remove("hidden");

            axios.get('/dashboard/call-center/get-dispatcher')
                .then(response => {
                    const data = response.data.data || [];
                    table.clear();

                    data.forEach(item => {
                        const statusBadge = item.online === 'online' ?
                            '<span class="badge badge-online">Online</span>' :
                            '<span class="badge badge-offline">Offline</span>';

                        table.row.add([
                            item.name || '-',
                            item.email || '-',
                            item.dinas_name || '-',
                            item.updated_at || '-',
                            statusBadge
                        ]);
                    });

                    table.draw();
                    document.getElementById("loading").classList.add("hidden");
                })
                .catch(error => {
                    console.error('Terjadi kesalahan saat mengambil data:', error);
                    document.getElementById("loading").classList.add("hidden");
                });
        }

        loadDispatcherData();
    });
</script>

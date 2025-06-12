<div class="card shadow-md rounded-lg border border-gray-200">
    <div class="card-body p-4">
        <!-- Title Section -->
        <div class="flex items-center justify-between mb-4">
            <h5 class="text-lg font-semibold text-gray-800">
                Dispatcher Table
            </h5>
            <div id="onlineStatusInfo" class="text-sm text-green-600 font-medium">
                <!-- Diisi via JS: "3 user online" -->
            </div>
        </div>

        <!-- Table Section -->
        <div class="table-responsive table-icon overflow-x-auto relative">
            <table class="table min-w-full text-sm text-left border-collapse" id="dispatcherTable">
                <thead class="text-xs text-gray-500 uppercase border-b border-gray-300">
                    <tr>
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4" width="220">Dinas</th>
                        <th class="py-3 px-4">Terakhir Update</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data akan di-inject -->
                </tbody>
            </table>

            <!-- Loading Overlay -->
            <div id="loading"
                class="hidden absolute inset-0 bg-white bg-opacity-70 flex justify-center items-center z-10">
                <div
                    class="inline-block w-10 h-10 border-4 border-blue-500 border-t-transparent rounded-full animate-spin">
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    /* Hover row highlight */
    #dispatcherTable tbody tr:hover {
        background-color: #f3f4f6;
        /* Tailwind gray-100 */
        cursor: pointer;
    }

    /* Status badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
        text-align: center;
        white-space: nowrap;
    }

    .badge-online {
        background-color: #10b981;
        /* emerald-500 */
        color: white;
    }

    .badge-offline {
        background-color: #ef4444;
        /* red-500 */
        color: white;
    }

    /* Custom pagination styles */
    .dataTables_wrapper .dataTables_paginate {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
        gap: 0.5rem;
    }

    .dataTables_wrapper .paginate_button {
        padding: 0.5rem 0.8rem;
        border-radius: 0.375rem;
        /* rounded-md */
        border: 1px solid transparent;
        background-color: #f3f4f6;
        /* gray-100 */
        color: #374151;
        /* gray-700 */
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s, color 0.2s, border-color 0.2s;
        user-select: none;
    }

    .dataTables_wrapper .paginate_button:hover:not(.disabled) {
        background-color: #2563eb;
        /* blue-600 */
        color: white;
        border-color: #2563eb;
    }

    .dataTables_wrapper .paginate_button.current {
        background-color: #2563eb;
        /* blue-600 */
        color: white !important;
        border-color: #2563eb !important;
        cursor: default;
    }

    .dataTables_wrapper .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Next & Prev buttons with icons (optional) */
    .dataTables_wrapper .paginate_button.previous::before {
        content: "← ";
    }

    .dataTables_wrapper .paginate_button.next::after {
        content: " →";
    }
</style>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports - UDINIT</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/navbar/navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/dashboard/sidebars/sidebars.css') }}" />
    <style>
        .reports-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .reports-header {
            margin-bottom: 2rem;
        }

        .reports-title {
            font-size: 2rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 0.5rem;
        }

        .reports-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #1e293b;
            color: #f1f5f9;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 1.5rem;
        }

        .back-button:hover {
            background: #334155;
            border-color: #475569;
        }

        .reports-table-container {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reports-table thead {
            background: #1e293b;
        }

        .reports-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #f1f5f9;
            border-bottom: 1px solid #334155;
            white-space: nowrap;
        }

        .reports-table td {
            padding: 1rem;
            color: #cbd5e1;
            border-bottom: 1px solid #1e293b;
        }

        .reports-table tbody tr {
            transition: background 0.2s;
            cursor: pointer;
        }

        .reports-table tbody tr:hover {
            background: #1e293b;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-activo {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-en_progreso {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .status-finalizado {
            background: rgba(148, 163, 184, 0.1);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .filters-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-input,
        .filter-select {
            flex: 1;
            min-width: 200px;
            padding: 0.75rem;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            color: #f1f5f9;
            font-size: 0.95rem;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .case-code {
            font-family: monospace;
            color: #3b82f6;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <header class="navbar">
        <div class="navbar-inner app-max-width">
            <div class="navbar-left">
                <div class="navbar-logo">
                    <span class="navbar-logo-mark">U</span>
                    <span class="navbar-logo-text">UDINIT</span>
                </div>
            </div>

            <div class="navbar-right">
                <div class="navbar-user">
                    <button class="navbar-user-trigger" id="userMenuToggle" type="button" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="navbar-user-avatar">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path
                                    d="M12 2a5 5 0 0 1 5 5v1a5 5 0 0 1-10 0V7a5 5 0 0 1 5-5Zm0 11a3 3 0 0 0 3-3V7a3 3 0 0 0-6 0v3a3 3 0 0 0 3 3Zm0 2c3.31 0 6 2.02 6 4.5V21h-2v-1.5c0-1.41-1.79-2.5-4-2.5s-4 1.09-4 2.5V21H6v-1.5C6 17.02 8.69 15 12 15Z" />
                            </svg>
                        </span>
                        <span class="navbar-user-chevron">▾</span>
                    </button>

                    <div class="navbar-user-menu" id="userMenu">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="navbar-user-item" type="submit">
                                <span class="navbar-user-item-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M11 2h2v10h-2V2Zm-4.64 2.64 1.41 1.41A6 6 0 1 0 18 10a5.94 5.94 0 0 0-1.77-4.24l1.41-1.41A8 8 0 1 1 6.36 4.64Z" />
                                    </svg>
                                </span>
                                <span class="navbar-user-item-label">Salir</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- REPORTS CONTENT -->
    <div class="reports-container">
        <a href="{{ route('dashboard') }}" class="back-button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to Dashboard
        </a>

        <div class="reports-header">
            <div class="reports-title">Case Reports</div>
            <div class="reports-subtitle">View detailed reports of all cases in the system</div>
        </div>

        <!-- Filters -->
        <div class="filters-container">
            <input type="text" id="searchInput" class="filter-input" placeholder="Search by Case ID or Description...">
            <select id="statusFilter" class="filter-select">
                <option value="">All Statuses</option>
                <option value="activo">Activo</option>
                <option value="en_progreso">En Progreso</option>
                <option value="finalizado">Finalizado</option>
            </select>
        </div>

        <!-- Reports Table -->
        <div class="reports-table-container">
            <table class="reports-table" id="reportsTable">
                <thead>
                    <tr>
                        <th>Case ID</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($casos as $caso)
                        <tr data-status="{{ $caso->estado }}"
                            onclick="window.location.href='{{ route('reports.show', $caso->id_caso) }}';">
                            <td><span class="case-code">{{ $caso->codigo_caso ?? 'N/A' }}</span></td>
                            <td>
                                <span class="status-badge status-{{ str_replace(' ', '_', strtolower($caso->estado)) }}">
                                    <span class="status-dot"></span>
                                    {{ ucfirst($caso->estado) }}
                                </span>
                            </td>
                            <td>{{ $caso->descripcion }}</td>
                            <td>{{ \Carbon\Carbon::parse($caso->fecha_creacion)->format('M d, Y') }}</td>
                            <td>{{ $caso->prioridad ?? 'Medium' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: #64748b;">
                                No cases found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/dashboard/navbar/navbar.js') }}"></script>
    <script>
        // Simple filter functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const table = document.getElementById('reportsTable');
        const rows = table.querySelectorAll('tbody tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value.toLowerCase();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const status = row.getAttribute('data-status') || '';

                const matchesSearch = text.includes(searchTerm);
                const matchesStatus = !selectedStatus || status.toLowerCase() === selectedStatus;

                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mis Casos - UDINIT</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('assets/dashboard/index.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/navbar/navbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/components/components.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/capturista/capturista.css') }}" />
</head>
<body>
  <!-- NAVBAR -->
  @include('partials.navbar')

  <!-- CONTENIDO PRINCIPAL -->
  <div class="capturista-container">
    <div class="capturista-header">
      <h1 class="capturista-title">Mis Casos</h1>
      <p class="capturista-subtitle">Gestiona los casos asignados a ti</p>
    </div>

    <!-- FILTROS Y BÚSQUEDA -->
    <div class="capturista-filters">
      <div class="filter-search">
        <span class="filter-search-icon">
          <svg viewBox="0 0 24 24">
            <path d="M10 2a8 8 0 1 1-5.3 14l-3.1 3.1-1.4-1.4 3.1-3.1A8 8 0 0 1 10 2Zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12Z" />
          </svg>
        </span>
        <input type="search" id="searchCasos" placeholder="Buscar casos por código o descripción..." />
      </div>
      
      <div class="filter-group">
        <button class="filter-btn active" data-filter="todos">Todos</button>
        <button class="filter-btn" data-filter="activo">Activos</button>
        <button class="filter-btn" data-filter="en_progreso">En Progreso</button>
        <button class="filter-btn" data-filter="finalizado">Finalizados</button>
      </div>
    </div>

    <!-- GRID DE CASOS -->
    <div class="casos-grid" id="casosGrid">
      @forelse($casos as $caso)
        <a href="{{ route('capturista.caso-detalle', $caso->id_caso) }}" class="caso-card-link" data-estado="{{ $caso->estado }}">
          <article class="caso-card-full">
            <header class="caso-header">
              <div class="estado-badge {{ $caso->estado == 'activo' ? 'estado-activo' : ($caso->estado == 'en_progreso' ? 'estado-progreso' : 'estado-finalizado') }}">
                <div class="estado-icono"></div>
                <span>{{ ucfirst(str_replace('_', ' ', $caso->estado)) }}</span>
              </div>
            </header>
            <div class="caso-body">
              <p>{{ $caso->descripcion }}</p>
            </div>
            <footer class="caso-footer">
              <span>CASE ID:</span>
              <span class="caso-codigo">{{ $caso->codigo_caso ?? 'N/A' }}</span>
            </footer>
          </article>
        </a>
      @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
          <div class="empty-state-icon">
            <svg viewBox="0 0 24 24">
              <path d="M9 3V2h6v1h4a2 2 0 0 1 2 2v4H3V5a2 2 0 0 1 2-2h4Zm12 7H3v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9Z" />
            </svg>
          </div>
          <h3 class="empty-state-title">No hay casos asignados</h3>
          <p class="empty-state-description">Actualmente no tienes casos asignados. Contacta con tu supervisor para más información.</p>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets/dashboard/components/toast.js') }}"></script>
  <script src="{{ asset('assets/dashboard/components/loading.js') }}"></script>
  <script src="{{ asset('assets/dashboard/navbar/navbar.js') }}"></script>
  <script src="{{ asset('assets/dashboard/capturista/casos.js') }}"></script>
</body>
</html>

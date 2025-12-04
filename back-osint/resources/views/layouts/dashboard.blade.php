<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>UDINIT Dashboard</title>

  <!-- CSS global y de componentes -->
  <link rel="stylesheet" href="{{ asset('assets/dashboard/index.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/navbar/navbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/sidebars/sidebars.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/components/components.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/dashboard/main-content/main-content.css') }}" />
  @stack('styles')
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

        <div class="navbar-search">
          <span class="navbar-search-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24">
              <path d="M10 2a8 8 0 1 1-5.3 14l-3.1 3.1-1.4-1.4 3.1-3.1A8 8 0 0 1 10 2Zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12Z" />
            </svg>
          </span>
          <input
            type="search"
            placeholder="Search incidents, users, IPs..."
            aria-label="Buscar"
          />
        </div>
      </div>

      <nav class="navbar-center" aria-label="Navegación principal">
        <button class="navbar-icon-btn active" type="button" aria-label="Home" onclick="window.location.href='{{ route('dashboard') }}'">
          <svg viewBox="0 0 24 24">
            <path d="M3 10.5 12 3l9 7.5V21h-7v-5h-4v5H3Z" />
          </svg>
        </button>

        <button class="navbar-icon-btn" type="button" aria-label="Portafolio">
          <svg viewBox="0 0 24 24">
            <path d="M9 3V2h6v1h4a2 2 0 0 1 2 2v4H3V5a2 2 0 0 1 2-2h4Zm12 7H3v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9Z" />
          </svg>
        </button>

        <button class="navbar-icon-btn" type="button" aria-label="Personas">
          <svg viewBox="0 0 24 24">
            <path d="M8 11a3 3 0 1 1 3-3 3 3 0 0 1-3 3Zm8 0a3 3 0 1 1 3-3 3 3 0 0 1-3 3ZM8 13a4 4 0 0 0-4 4v2h8v-2a4 4 0 0 0-4-4Zm8 0a4 4 0 0 0-4 4v2h8v-2a4 4 0 0 0-4-4Z" />
          </svg>
        </button>

        <button class="navbar-icon-btn" type="button" aria-label="Huella">
          <svg viewBox="0 0 24 24">
            <path d="M12 2a7 7 0 0 0-7 7v1h2V9a5 5 0 0 1 10 0 16 16 0 0 1-3 9.64L12 22l-2-1.36A18 18 0 0 0 7 9H5a20 20 0 0 0 4 13l3 2 3-2A18 18 0 0 0 19 9a7 7 0 0 0-7-7Z" />
          </svg>
        </button>
      </nav>

      <div class="navbar-right">
        <button class="navbar-icon-btn" type="button" aria-label="Notificaciones">
          <svg viewBox="0 0 24 24">
            <path d="M12 22a2 2 0 0 0 2-2h-4a2 2 0 0 0 2 2Zm6-6V11a6 6 0 0 0-5-5.91V4a1 1 0 0 0-2 0v1.09A6 6 0 0 0 6 11v5l-2 2v1h16v-1Z" />
          </svg>
        </button>

        <div class="navbar-user">
          <button
            class="navbar-user-trigger"
            id="userMenuToggle"
            type="button"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <span class="navbar-user-avatar">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 2a5 5 0 0 1 5 5v1a5 5 0 0 1-10 0V7a5 5 0 0 1 5-5Zm0 11a3 3 0 0 0 3-3V7a3 3 0 0 0-6 0v3a3 3 0 0 0 3 3Zm0 2c3.31 0 6 2.02 6 4.5V21h-2v-1.5c0-1.41-1.79-2.5-4-2.5s-4 1.09-4 2.5V21H6v-1.5C6 17.02 8.69 15 12 15Z" />
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
                    <path d="M11 2h2v10h-2V2Zm-4.64 2.64 1.41 1.41A6 6 0 1 0 18 10a5.94 5.94 0 0 0-1.77-4.24l1.41-1.41A8 8 0 1 1 6.36 4.64Z" />
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

  <!-- LAYOUT: BARRAS LATERALES + CONTENIDO CENTRAL -->
  <div class="layout-wrapper">
    <div class="layout-shell app-max-width">
      <!-- BARRA LATERAL IZQUIERDA (UDINIT) -->
      <aside class="sidebar izquierda" id="sidebarIzquierda">
        <button
          class="sidebar-toggle sidebar-toggle-izquierda"
          type="button"
          aria-label="Ocultar barra izquierda"
        >
          &#x2039;
        </button>
        <div class="sidebar-content">
          <div class="sidebar-header">
            <div class="sidebar-title">UDINIT</div>
          </div>
          <nav class="sidebar-menu" aria-label="Fuentes de datos">
            <button class="sidebar-item" type="button">Email address</button>
            <button class="sidebar-item" type="button">Domain name</button>
            <button class="sidebar-item" type="button">IP &amp; MAC address</button>
            <button class="sidebar-item" type="button">Social networks</button>
            <button class="sidebar-item" type="button">Telephone numbers</button>
            <button class="sidebar-item" type="button">Geolocation</button>
            <button class="sidebar-item" type="button">Dark Web</button>
          </nav>
        </div>
      </aside>

      <!-- ZONA DE CONTENIDO CENTRAL -->
      <main class="contenido-central">
        <div class="contenido-wrapper">
            @yield('content')
        </div>
      </main>

      <!-- BARRA LATERAL DERECHA (TOOLS) -->
      <aside class="sidebar derecha" id="sidebarDerecha">
        <button
          class="sidebar-toggle sidebar-toggle-derecha"
          type="button"
          aria-label="Ocultar barra derecha"
        >
          &#x203A;
        </button>
        <div class="sidebar-content">
          <div class="sidebar-header">
            <div class="sidebar-title">TOOLS</div>
          </div>
          <nav class="sidebar-menu" aria-label="Herramientas">
            @if(isset($herramientas))
                @foreach($herramientas as $herramienta)
                <button class="sidebar-item" type="button">{{ $herramienta->nombre }}</button>
                @endforeach
            @else
                <p style="padding: 1rem; color: var(--text-secondary);">No tools available</p>
            @endif
          </nav>
        </div>
      </aside>
    </div>
  </div>

  <!-- JS global y de componentes -->
  <script src="{{ asset('assets/dashboard/navbar/navbar.js') }}"></script>
  <script src="{{ asset('assets/dashboard/sidebars/sidebars.js') }}"></script>
  <script src="{{ asset('assets/dashboard/main-content/main-content.js') }}"></script>
  <script src="{{ asset('assets/dashboard/index.js') }}"></script>
  @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultor - Sistema OSINT</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar h1 {
            font-size: 24px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid white;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .welcome-card h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #666;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card h3 {
            color: #667eea;
            font-size: 22px;
            margin-bottom: 15px;
            text-align: center;
        }

        .stat-options {
            list-style: none;
            padding-left: 10px;
        }

        .stat-options li {
            margin: 8px 0;
            cursor: pointer;
            color: #444;
            font-size: 15px;
            transition: color 0.2s;
        }

        .stat-options li:hover {
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <h1>Sistema OSINT - Consultor</h1>
        <div class="user-info">
            <span id="userName">{{ Auth::user()->nombre }}</span>
            <button class="btn-logout" onclick="logout()">Cerrar Sesión</button>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h2>Bienvenido al Sistema OSINT para Consultor</h2>
            <p>Aquí podrás acceder a los módulos correspondientes a tu rol.</p>
        </div>

        <div class="stats-grid">

            <!-- USUARIOS -->
            <div class="stat-card">
                <h3>Usuarios</h3>
                <ul class="stat-options">
                    <li onclick="goTo('consultor/usuarios/lista-usuarios')">Lista de usuarios activos</li>
                    <!-- <li onclick="goTo('consultor/usuarios/detalle-usuario')">Detalle de usuario</li> -->
                </ul>
            </div>

            <!-- CASOS -->
            <div class="stat-card">
                <h3>Casos</h3>
                <ul class="stat-options">
                    <li onclick="goTo('consultor/casos/lista-casos')">Lista de casos</li>
                    <!-- <li onclick="goTo('consultor/casos/detalle-caso.html')">Detalle de caso con creador</li>
                    <li onclick="goTo('consultor/casos/usuarios-asignados.html')">Usuarios asignados a un caso</li> -->
                </ul>
            </div>

            <!-- EVIDENCIAS -->
            <div class="stat-card">
                <h3>Evidencias</h3>
                <!-- <ul class="stat-options">
                    <li onclick="goTo('consultor/evidencias/evidencias-caso.html')">Evidencias de un caso</li>
                </ul> -->
            </div>

            <!-- HERRAMIENTAS -->
            <div class="stat-card">
                <h3>Herramientas</h3>
                <!-- <ul class="stat-options">
                    <li onclick="goTo('consultor/herramientas/lista-herramientas.html')">Lista de herramientas</li>
                    <li onclick="goTo('consultor/herramientas/herramientas-categoria.html')">Herramientas de una
                        categoría</li>
                </ul> -->
            </div>

            <!-- ACCIONES E HISTORIAL -->
            <div class="stat-card">
                <h3>Acciones e historial</h3>
                <!-- <ul class="stat-options">
                    <li onclick="goTo('consultor/acciones/acciones-usuario.html')">Acciones de un usuario</li>
                    <li onclick="goTo('consultor/acciones/historial-caso.html')">Historial de un caso</li>
                </ul> -->
            </div>

            <!-- PLATAFORMAS -->
            <div class="stat-card">
                <h3>Plataformas</h3>
                <!-- <ul class="stat-options">
                    <li onclick="goTo('consultor/plataformas/plataformas-vinculadas.html')">Plataformas vinculadas</li>
                </ul> -->

            </div>

        </div>
    </div>

    <script>
        // Logout
        function logout() {
            // Submit the logout form
            document.getElementById('logout-form').submit();
        }

        function goTo(url) {
            window.location.href = "{{ url('/') }}/" + url;
        }
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

</body>

</html>
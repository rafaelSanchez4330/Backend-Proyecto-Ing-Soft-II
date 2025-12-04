<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Caso - Consultor</title>

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
            font-size: 22px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .case-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .case-card h2 {
            color: #444;
            margin-bottom: 10px;
        }

        .case-info p {
            margin: 6px 0;
            color: #555;
            font-size: 15px;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }

        .option-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform .2s;
            text-align: center;
        }

        .option-card:hover {
            transform: scale(1.03);
        }

        .option-card h3 {
            margin-bottom: 10px;
            color: #667eea;
        }

        .btn-back {
            margin-top: 25px;
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background .3s;
        }

        .btn-back:hover {
            background: #5567d9;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <h1>Detalle del Caso</h1>
        <span id="userSession">{{ Auth::user()->nombre }}</span>
    </nav>

    <div class="container">

        <div class="case-card">
            <h2 id="nombreCaso">{{ $caso->nombre }}</h2>

            <div class="case-info">
                <p><strong>ID:</strong> <span id="idCaso">{{ $caso->id_caso }}</span></p>
                <p><strong>Tipo:</strong> <span id="tipoCaso">{{ $caso->tipo_caso }}</span></p>
                <p><strong>Estado:</strong> <span id="estadoCaso">{{ $caso->estado }}</span></p>
                <p><strong>Creador:</strong> <span
                        id="creadorCaso">{{ $caso->creador ? $caso->creador->nombre : 'Desconocido' }}</span></p>
                <p><strong>Descripción:</strong> <span id="descripcionCaso">{{ $caso->descripcion }}</span></p>
                <p><strong>Fecha creación:</strong> <span id="fechaCreacion">{{ $caso->fecha_creacion }}</span></p>
                <p><strong>Fecha actualización:</strong> <span
                        id="fechaActualizacion">{{ $caso->fecha_actualizacion ?? 'Sin cambios' }}</span></p>
            </div>
        </div>

        <!-- <div class="options-grid">
            <div class="option-card" onclick="verUsuariosAsignados()">
                <h3>Usuarios asignados</h3>
                <p>Consultar participantes del caso.</p>
            </div>

            <div class="option-card" onclick="verEvidencias()">
                <h3>Evidencias del caso</h3>
                <p>Ver archivos y registros adjuntos.</p>
            </div>

            <div class="option-card" onclick="verHistorial()">
                <h3>Historial del caso</h3>
                <p>Movimientos y acciones realizadas.</p>
            </div>
        </div> -->

        <a href="{{ route('consultor.casos.index') }}" class="btn-back">← Volver a Casos</a>

    </div>

    <script>
        const casoId = {{ $caso->id_caso }};

        function verUsuariosAsignados() {
            // Placeholder link
            window.location.href = `/consultor/casos/usuarios-asignados?id=${casoId}`;
        }

        function verEvidencias() {
            // Placeholder link
            window.location.href = `/consultor/evidencias/evidencias-caso?id=${casoId}`;
        }

        function verHistorial() {
            // Placeholder link
            window.location.href = `/consultor/acciones/historial-caso?id=${casoId}`;
        }

    </script>

</body>

</html>
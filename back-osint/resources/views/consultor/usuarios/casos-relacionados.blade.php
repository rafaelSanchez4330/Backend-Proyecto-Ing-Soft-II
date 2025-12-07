<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casos Relacionados - Consultor</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            margin: 0;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 22px;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background: #667eea;
            color: white;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #eee;
        }

        tbody tr:hover {
            background: #f0f0ff;
            cursor: pointer;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .btn-volver:hover {
            background: #5567d9;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <h1>Casos Relacionados</h1>
        <span>{{ Auth::user()->nombre }}</span>
    </nav>

    <div class="container">

        <h2>Casos creados por: {{ $usuario->nombre }}</h2>

        <table>
            <thead>
                <tr>
                    <th>ID Caso</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($casos as $caso)
                <tr onclick="window.location.href='{{ route('consultor.casos.show', $caso->id_caso) }}'">
                    <td>{{ $caso->id_caso }}</td>
                    <td>{{ $caso->estado }}</td>
                    <td>{{ $caso->nombre }}</td>
                    <td>{{ $caso->tipo_caso }}</td>
                    <td>{{ $caso->descripcion }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('consultor.usuarios.index') }}" class="btn-volver">← Volver</a>

    </div>

</body>
</html>

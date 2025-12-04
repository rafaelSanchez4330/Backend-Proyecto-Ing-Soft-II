<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Casos - Consultor</title>

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
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h1 {
            font-size: 22px;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        thead {
            background: #667eea;
            color: white;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            font-size: 15px;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            cursor: pointer;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f0f0ff;
        }

        .btn-back {
            display: inline-block;
            margin-top: 25px;
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
        <h1>Lista de Casos</h1>
        <span id="userSession">{{ Auth::user()->nombre }}</span>
    </nav>

    <div class="container">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Caso</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Creador</th>
                </tr>
            </thead>
            <tbody id="tablaCasos">
                @foreach($casos as $caso)
                <tr onclick="window.location.href='{{ route('consultor.casos.show', $caso->id_caso) }}'">
                    <td>{{ $caso->id_caso }}</td>
                    <td>{{ $caso->nombre }}</td>
                    <td>{{ $caso->tipo_caso }}</td>
                    <td>{{ $caso->estado }}</td>
                    <td>{{ $caso->creador ? $caso->creador->nombre : 'Desconocido' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('consultor.inicio') }}" class="btn-back">← Volver al Panel</a>

    </div>

</body>
</html>
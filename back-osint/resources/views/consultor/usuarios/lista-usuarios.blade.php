<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Activos - Consultor</title>
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
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 15px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border-bottom: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            color: #333;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .btn-ver {
            background: #667eea;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-ver:hover {
            background: #5568d8;
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
        <h1>Sistema OSINT - Consultor</h1>
        <span id="userName">{{ Auth::user()->nombre }}</span>
    </nav>

    <div class="container">
        <h2>Lista de Usuarios Activos</h2>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody id="tablaUsuarios">
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->usuario }}</td>
                        <td>{{ $usuario->mail }}</td>
                        <td>{{ $usuario->rol }}</td>
                        <td>
                            <a href="{{ route('consultor.usuarios.show', $usuario->id_usuario) }}" class="btn-ver"
                                style="text-decoration: none;">
                                Ver
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('consultor.inicio') }}" class="btn-back">← Volver al Panel</a>
    </div>

</body>

</html>
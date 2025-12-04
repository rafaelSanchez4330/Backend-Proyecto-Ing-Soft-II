<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Usuario - Consultor</title>

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
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .campo {
            margin-bottom: 15px;
        }

        .campo label {
            font-weight: bold;
            color: #555;
        }

        .campo span {
            display: block;
            color: #111;
        }

        .btn-volver {
            margin-top: 25px;
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-volver:hover {
            background: #5568d8;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <h1>Sistema OSINT - Consultor</h1>
        <span id="userName">{{ Auth::user()->nombre }}</span>
    </nav>

    <div class="container">
        <h2>Detalle del Usuario</h2>

        <div class="campo">
            <label>Nombre:</label>
            <span id="nombre">{{ $usuario->nombre }}</span>
        </div>

        <div class="campo">
            <label>Usuario:</label>
            <span id="usuario">{{ $usuario->usuario }}</span>
        </div>

        <div class="campo">
            <label>Email:</label>
            <span id="mail">{{ $usuario->mail }}</span>
        </div>

        <div class="campo">
            <label>Rol:</label>
            <span id="rol">{{ $usuario->rol }}</span>
        </div>

        <a href="{{ route('consultor.usuarios.index') }}" class="btn-volver">Volver</a>
    </div>

</body>

</html>
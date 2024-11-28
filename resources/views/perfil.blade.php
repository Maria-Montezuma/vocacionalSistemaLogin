<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ddd;
            margin-right: 20px;
        }

        .profile-info h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .profile-info p {
            color: #666;
        }

        .profile-details {
            margin-top: 20px;
        }

        .detail-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .logout-btn {
            background-color: #ff4444;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-pic"></div>
                <div class="profile-info">
                    <h1>{{ session('NombreUsuario') }}</h1>
                    <p>{{ session('CorreoUsuario') }}</p>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-item">
                    <strong>ID de Usuario:</strong> {{ session('idUsuario') }}
                </div>
                <div class="detail-item">
                    <strong>Correo:</strong> {{ session('CorreoUsuario') }}
                </div>
                <div class="detail-item">
                    <strong>Nombre:</strong> {{ session('NombreUsuario') }}
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
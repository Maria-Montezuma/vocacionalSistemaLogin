<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
</head>
<body>
    <h1>Recuperación de Contraseña</h1>
    <p>Hola {{ $usuario->NombreUsuario }},</p>
    <p>Has solicitado restablecer tu contraseña. Por favor, haz clic en el siguiente enlace para crear una nueva contraseña:</p>
    
    <a href="{{ $resetUrl }}" style="background-color: #4CAF50; color: white; padding: 14px 20px; text-align: center; text-decoration: none; display: inline-block;">
        Restablecer Contraseña
    </a>

    <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
    <p>Este enlace expirará en 1 hora.</p>
</body>
</html>
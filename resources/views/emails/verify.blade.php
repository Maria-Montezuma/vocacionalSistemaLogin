<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de correo electrónico</title>
</head>
<body>
    <h1>Verificacion de Correo Electronico</h1>
    <p>Estimado/a {{ $usuario->NombreUsuario }}!</p>

    <p>Gracias por registrarte en nuestro sitio web. Para completar el proceso y activar tu cuenta, por fabor verifica tu correo electrónico haciendo clic en el siguiente enlace:</p>
    <p style="text-align: center;">
        <a href="{{ url('/verificar?token=' . $token) }}" style="background-color: #e2740e; color:  #ffffff; padding: 14px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block;">
            Verificar mi correo electrónico</a>
    </p>
    <p>Recuerda que este enlace tiene una validez de 24 horas. Si no realizaste este registro, por favor ignora este mensaje.</p>
    
    <p>Saludos cordiales,</p>
    <p>El equipo de OrientaPro</p>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de correo electrónico</title>
</head>
<body>
    <h1>Hola {{ $usuario->NombreUsuario }}!</h1>
    <p>Para completar tu registro, por favor haz clic en el siguiente enlace para verificar tu correo electrónico:</p>
    <p>
        <a href="{{ url('/verificar?token=' . $token) }}">Verificar mi correo electrónico</a>
    </p>
    <p>Este enlace expirará en 24 horas.</p>
    <p>Si no te has registrado en nuestro sitio, por favor ignora este mensaje.</p>
</body>
</html>

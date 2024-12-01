<!DOCTYPE html>
<html>
<head>
    <title>Verificación de Email</title>
</head>
<body>
    <h2>Hola {{ $usuario->NombreUsuario }}</h2>
    <p>Por favor haz clic en el siguiente enlace para verificar tu correo electrónico:</p>
    <a href="{{ route('verify.email', $token) }}">Verificar Email</a>
</body>
</html>
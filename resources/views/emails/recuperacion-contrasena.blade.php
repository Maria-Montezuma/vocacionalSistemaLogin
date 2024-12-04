<!DOCTYPE html>
<html>
<head>
    <title>Recuperación de Contraseña</title>
</head>
    <body>
    <h1>Recuperación de Contraseña</h1>
    <p>Estimado/a {{ $usuario->NombreUsuario }},</p>
    
    <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si fuiste tú quien realizó esta solicitud, por favor haz clic en el siguiente enlace para crear una nueva contraseña:</p>
    
    <p style="text-align: center;">
        <a href="{{ $resetUrl }}" style="background-color: #e2740e; color:  #ffffff; padding: 14px 20px; text-align: center; text-decoration: none; border-radius: 5px; display: inline-block;">
            Restablecer Contraseña
        </a>
    </p>

    <p>Si no solicitaste este cambio, puedes ignorar este mensaje y no se realizará ningún cambio en tu cuenta.</p>
    <p>Ten en cuenta que este enlace expirará en 1 hora por razones de seguridad. Si no logras restablecer tu contraseña dentro de este plazo, por favor solicita un nuevo enlace.</p>

    <p>Saludos cordiales,</p>
    <p>El equipo de soporte de OrientaPro</p>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <header class="profile-header">
            <h1>Bienvenido, {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</h1>
            <nav class="profile-nav">
                <ul>
                    <li><a href="{{ route('perfil') }}">Mi Perfil</a></li>
                    <li><a href="{{ route('logout') }}">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>

        <main class="profile-content">
            <section class="user-info">
                <h2>Información Personal</h2>
                <p><strong>Cédula:</strong> {{ Auth::user()->cedula }}</p>
                <p><strong>Nacionalidad:</strong> {{ Auth::user()->nacionalidad }}</p>
                <p><strong>Fecha de Nacimiento:</strong> {{ Auth::user()->fecha_nacimiento }}</p>
                <p><strong>Correo Electrónico:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Dirección:</strong> {{ Auth::user()->direccion }}</p>
                <p><strong>Descripción:</strong> {{ Auth::user()->descripcion }}</p>
            </section>

            <section class="profile-actions">
                <h2>Acciones</h2>
                <button onclick="alert('Funcionalidad no implementada')">Editar Perfil</button>
                <button onclick="alert('Funcionalidad no implementada')">Cambiar Contraseña</button>
            </section>
        </main>

        <footer class="profile-footer">
            <p>&copy; {{ date('Y') }} Mi Aplicación. Todos los derechos reservados.</p>
        </footer>
    </div>
</body>
</html>
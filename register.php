<?php
    require_once './php/sesion.php';

    if (usuarioAutenticado()) {
        header('Location: ./index.php');
        exit;
    }

    $titulo = 'Registro de Eventos | Nuevo usuario';
    $error = $_SESSION['error'] ?? null;
    $correcto = $_SESSION['correcto'] ?? null;
    unset($_SESSION['error'], $_SESSION['correcto']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/icono.png">
    <title><?= htmlspecialchars($titulo) ?></title>
</head>
<body>
    <header class="cabecera">
        <nav class="navegacion navegacion--auth">
            <img class="logo-proyecto" src="./img/logo.png" alt="Logo del evento">
            <div class="navegacion__contenido">
                <h1 class="titulo-proyecto">Registro de Eventos</h1>
                <div class="navegacion__acciones">
                    <a class="enlace-cabecera enlace-cabecera--secundario" href="./register.php">Registro</a>
                    <a class="enlace-cabecera" href="./login.php">Iniciar sesión</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="register-page">
        <section class="card">
            <h1>Registrar nuevo usuario</h1>

            <?php if ($correcto): ?>
                <div class="alert success"><?= htmlspecialchars($correcto) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="./php/procesar_registro_usuario.php" method="post" novalidate>
                <label for="nombre">Nombre:</label>
                <input id="nombre" name="nombre" type="text" placeholder="Alfonso" required>

                <label for="apellido1">Primer apellido:</label>
                <input id="apellido1" name="apellido1" type="text" placeholder="García" required>

                <label for="apellido2">Segundo apellido:</label>
                <input id="apellido2" name="apellido2" type="text" placeholder="López">

                <label for="localidad">Localidad:</label>
                <input id="localidad" name="localidad" type="text" placeholder="Sevilla">

                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                <input id="fecha_nacimiento" name="fecha_nacimiento" type="date">

                <label for="correo">Correo electrónico:</label>
                <input id="correo" name="correo" type="email" placeholder="usuario@ejemplo.com" required pattern="[A-Za-z0-9._%+-]+@educarex\.es" title="El correo debe pertenecer al dominio educarex.es">

                <label for="contrasena">Contraseña:</label>
                <input id="contrasena" name="contrasena" type="password" minlength="8" placeholder="••••••••" required>

                <label for="contrasena2">Confirmar contraseña:</label>
                <input id="contrasena2" name="contrasena2" type="password" minlength="8" placeholder="••••••••" required>

                <div class="form-nav">
                    <button type="button" class="btn btn-volver" onclick="window.location.href='./index.php'">Volver a inicio</button>
                    <button type="submit" class="btn btn-enviar">Crear cuenta</button>
                </div>
            </form>

            <p class="login-link">¿Ya tienes una cuenta? <a href="./login.php">Inicia sesión aquí</a>.</p>
        </section>
    </main>
</body>
</html>

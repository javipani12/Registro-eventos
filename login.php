<?php
    require_once './php/sesion.php';

    if (usuarioAutenticado()) {
        header('Location: ./index.php');
        exit;
    }

    $titulo = 'Registro de Eventos | Iniciar sesión';
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
            <h1>Iniciar sesión</h1>

            <?php if ($correcto): ?>
                <div class="alert success"><?= htmlspecialchars($correcto) ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="./php/procesar_login.php" method="post" novalidate>
                <label for="email">Correo electrónico:</label>
                <input id="email" name="email" required type="email" placeholder="usuario@ejemplo.com">

                <label for="passwd">Contraseña:</label>
                <input id="passwd" name="passwd" required type="password" placeholder="••••••••">

                <div class="form-nav">
                    <button type="button" class="btn btn-volver" onclick="window.location.href='./index.php'">Volver a inicio</button>
                    <button type="submit" class="btn btn-siguiente">Acceder</button>
                </div>
            </form>

            <p class="login-link">¿No tienes cuenta? <a href="./register.php">Regístrate aquí</a>.</p>
        </section>
    </main>
</body>
</html>

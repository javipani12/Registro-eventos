<?php
    require_once './php/sesion.php';
    $usuario = obtenerUsuarioSesion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/icono.png">
    <title>Registro de Eventos</title>
</head>
<body>
    <header class="cabecera">
        <nav class="navegacion">
            <img class="logo-proyecto" src="./img/logo.png" alt="Logo del evento">
            <h1 class="titulo-proyecto">Registro de Eventos</h1>
            <div class="acciones-cabecera">
                <?php if ($usuario): ?>
                    <a class="enlace-cabecera enlace-cabecera--logout" href="./php/cerrar_sesion.php">Cerrar sesión</a>
                <?php else: ?>
                    <a class="enlace-cabecera" href="./login.php">Iniciar sesión</a>
                    <a class="enlace-cabecera enlace-cabecera--secundario" href="./register.php">Registrarse</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="contenido-principal">
        <section class="tarjeta">
            <h3 class="tarjeta__titulo">Nuevo participante</h3>
            <?php if ($usuario): ?>
                <a class="tarjeta__accion" href="./formulario.php">Registrar participante</a>
            <?php else: ?>
                <span class="tarjeta__accion tarjeta__accion--deshabilitada" aria-disabled="true">Registrar participante</span>
            <?php endif; ?>
        </section>
        <section class="tarjeta">
            <h3 class="tarjeta__titulo">Participantes inscritos</h3>
            <?php if ($usuario): ?>
                <a class="tarjeta__accion tarjeta__accion--secundaria" href="./listado.php">Ver listado</a>
            <?php else: ?>
                <span class="tarjeta__accion tarjeta__accion--secundaria tarjeta__accion--deshabilitada" aria-disabled="true">Ver listado</span>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
<?php
    require_once './php/sesion.php';

    redirigirSiNoAutenticado();
    $usuario = obtenerUsuarioSesion() ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/icono.png">
    <title>Listado de participantes</title>
</head>
<body class="pagina-listado">
    <header class="cabecera">
        <nav class="navegacion navegacion--principal">
            <h1 class="titulo-proyecto">Listado de inscritos</h1>
            <div class="navegacion__acciones">
                <span class="navegacion__texto">Sesión iniciada<?= !empty($usuario['nombre']) ? ' como ' . htmlspecialchars((string) $usuario['nombre']) : '' ?>.</span>
                <a class="enlace-cabecera enlace-cabecera--logout" href="./php/cerrar_sesion.php">Cerrar sesión</a>
            </div>
        </nav>
    </header>
    <main class="contenido-listado">
        <header class="cabecera-listado">
            <h2 class="titulo-listado">Participantes registrados</h2>
            <button type="button" class="boton-volver" onclick="window.location.href='./index.php'">Volver</button>
        </header>

        <section class="buscador-listado">
            <input class="campo-busqueda" type="text" id="busqueda" placeholder="Buscar por nombre o NIF...">
            <button type="button" class="boton-listado" id="btn-buscar">Buscar</button>
            <button type="button" class="boton-listado boton-listado--secundario" id="btn-recargar">Recargar</button>
        </section>

        <section class="seccion-tabla">
            <table class="tabla-participantes">
                <thead>
                    <tr>
                        <th>NIF</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Edad</th>
                        <th>Domicilio</th>
                        <th>Población</th>
                        <th>Provincia</th>
                        <th>Código Postal</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Modalidad</th>
                    </tr>
                </thead>
                <tbody id="tabla-participantes-body">
                    <!-- Las filas de participantes se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </section>
    </main>
    <footer class="pie-pagina">
        <p>&copy; 2024 Listado de Participantes. Todos los derechos reservados.</p>
    </footer>
    <script src="./js/listado.js" defer></script>
</body>
</html>
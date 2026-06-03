<?php
    require_once './php/sesion.php';

    redirigirSiNoAutenticado();

    $usuario = obtenerUsuarioSesion() ?? [];
    $esNormal = esUsuarioNormal();
    $esAdmin = esAdmin();

    function edadDesdeFechaNacimiento(?string $fechaNacimiento){
        if ($fechaNacimiento === null || $fechaNacimiento === '') {
            return '18';
        }

        try {
            return (string) (new DateTimeImmutable($fechaNacimiento))->diff(new DateTimeImmutable('today'))->y;
        } catch (Throwable $e) {
            return '18';
        }
    }

    $nombre = $esNormal ? (string) ($usuario['nombre'] ?? '') : '';
    $apellidos = $esNormal ? trim((string) ($usuario['apellido1'] ?? '') . ' ' . (string) ($usuario['apellido2'] ?? '')) : '';
    $edadInicial = $esNormal ? edadDesdeFechaNacimiento($usuario['fecha_nacimiento'] ?? null) : '18';
    $poblacion = $esNormal ? (string) ($usuario['localidad'] ?? '') : '';
    $email = $esNormal ? (string) ($usuario['email'] ?? '') : '';
    $mensajeFormulario = $esAdmin
        ? 'Puedes registrar cualquier participante.'
        : 'Has iniciado sesión como usuario normal. El formulario se ha completado con tus datos y solo podrás registrarte a ti mismo.';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/estilos.css">
    <link rel="icon" type="image/png" sizes="32x32" href="./img/icono.png">
    <script src="./js/formulario.js" defer></script>
    <title>Registrar participante</title>
</head>
<body>
    <header class="cabecera">
        <nav class="navegacion navegacion--principal">
            <h1 class="titulo-proyecto">Registrar nuevo participante</h1>
            <div class="navegacion__acciones">
                <a class="enlace-cabecera enlace-cabecera--logout" href="./php/cerrar_sesion.php">Cerrar sesión</a>
            </div>
        </nav>
    </header>

    <section class="mensaje_formulario" id="mensaje-formulario">
        <p><?= htmlspecialchars($mensajeFormulario) ?></p>
    </section>
    
    <main class="contenido-formulario">
        <section class="tarjeta-formulario">
            <header class="cabecera-formulario">
                <h2 class="titulo-formulario">Registrar nuevo participante</h2>
                <button type="button" class="boton-volver" onclick="window.location.href='./index.php'">Volver</button>
            </header>

            <form action="./php/guardar_participante.php" method="POST" class="formulario-registro" novalidate>
                <div class="campo-formulario-grupo">
                    <label for="nif">NIF:</label>
                    <input type="text" id="nif" name="nif" class="campo-formulario" novalidate>
                    <span id="error-nif" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="campo-formulario" value="<?= htmlspecialchars($nombre) ?>" <?= $esNormal ? 'readonly' : '' ?> novalidate>
                    <span id="error-nombre" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="apellidos">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" class="campo-formulario" value="<?= htmlspecialchars($apellidos) ?>" <?= $esNormal ? 'readonly' : '' ?> novalidate>
                    <span id="error-apellidos" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="edad">Edad:</label>
                    <div class="grupo-edad">
                        <button id="boton_restar_edad" class="boton-cantidad" type="button">-</button>
                        <input type="number" id="edad" name="edad" class="campo-formulario campo-formulario--edad" value="<?= htmlspecialchars($edadInicial) ?>" <?= $esNormal ? 'readonly' : '' ?> novalidate>
                        <button id="boton_sumar_edad" class="boton-cantidad" type="button">+</button>
                    </div>
                    <span id="error-edad" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="domicilio">Domicilio:</label>
                    <input type="text" id="domicilio" name="domicilio" class="campo-formulario" novalidate>
                    <span id="error-domicilio" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="poblacion">Población:</label>
                    <input type="text" id="poblacion" name="poblacion" class="campo-formulario" value="<?= htmlspecialchars($poblacion) ?>" <?= $esNormal ? 'readonly' : '' ?> novalidate>
                    <span id="error-poblacion" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="provincia">Provincia:</label>
                    <input type="text" id="provincia" name="provincia" class="campo-formulario" novalidate>
                    <span id="error-provincia" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="cp">Código Postal:</label>
                    <input type="text" id="cp" name="cp" class="campo-formulario" novalidate>
                    <span id="error-cp" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" class="campo-formulario" novalidate>
                    <span id="error-telefono" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="campo-formulario" value="<?= htmlspecialchars($email) ?>" <?= $esNormal ? 'readonly' : '' ?> novalidate>
                    <span id="error-email" class="error"></span>
                </div>

                <div class="campo-formulario-grupo">
                    <label for="modalidad">Modalidad:</label>
                    <select id="modalidad" name="modalidad" class="campo-formulario" novalidate>
                        <option value="">-- Selecciona una modalidad --</option>
                        <option value="desarrollo_web">Desarrollo Web</option>
                        <option value="administracion_sistemas">Administración de Sistemas</option>
                        <option value="desarrollo_movil">Desarrollo Móvil</option>
                    </select>
                    <span id="error-modalidad" class="error"></span>
                </div>

                <div class="acciones-formulario">
                    <button type="reset" class="boton-formulario boton-formulario--secundario">Reiniciar</button>
                    <button type="submit" class="boton-formulario">Registrar</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
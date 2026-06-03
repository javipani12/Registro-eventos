<?php
    require_once './conexion.php';
    require_once './sesion.php';

    header('Content-Type: application/json; charset=utf-8');

    function enviar_respuesta(int $codigo, array $datos) {
        http_response_code($codigo);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;
    }

    function obtenerCampo(string $nombre) {
        return trim((string) filter_input(INPUT_POST, $nombre, FILTER_UNSAFE_RAW));
    }

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        enviar_respuesta(405, ['ok' => false, 'mensaje' => 'Método no permitido.']);
    }

    if (!usuarioAutenticado()) {
        enviar_respuesta(401, ['ok' => false, 'mensaje' => 'Debes iniciar sesión para registrar participantes.']);
    }

    $usuarioSesion = obtenerUsuarioSesion() ?? [];

        $nif = obtenerCampo('nif');
        $nombre = obtenerCampo('nombre');
        $apellidos = obtenerCampo('apellidos');
        $edad = (int) obtenerCampo('edad');
        $domicilio = obtenerCampo('domicilio');
        $poblacion = obtenerCampo('poblacion');
        $provincia = obtenerCampo('provincia');
        $codigoPostal = obtenerCampo('cp');
        $telefono = obtenerCampo('telefono');
        $email = obtenerCampo('email');
        $modalidad = obtenerCampo('modalidad');

    function normalizarTexto(string $texto) {
        $texto = trim(preg_replace('/\s+/', ' ', $texto) ?? '');
        return mb_strtolower($texto, 'UTF-8');
    }

    if (esUsuarioNormal()) {
        $nombreSesion = normalizarTexto((string) ($usuarioSesion['nombre'] ?? ''));
        $apellidosSesion = normalizarTexto(trim((string) ($usuarioSesion['apellido1'] ?? '') . ' ' . (string) ($usuarioSesion['apellido2'] ?? '')));
        $emailSesion = (string) ($usuarioSesion['email'] ?? '');
        $edadSesion = isset($usuarioSesion['edad']) ? (string) $usuarioSesion['edad'] : null;
        $poblacionSesion = (string) ($usuarioSesion['localidad'] ?? '');

        if (
            normalizarTexto($nombre) !== $nombreSesion ||
            normalizarTexto($apellidos) !== $apellidosSesion ||
            strcasecmp($email, $emailSesion) !== 0 ||
            ($edadSesion !== null && (string) $edad !== $edadSesion) ||
            ($poblacionSesion !== '' && normalizarTexto($poblacion) !== normalizarTexto($poblacionSesion))
        ) {
            enviar_respuesta(403, ['ok' => false, 'mensaje' => 'Solo puedes registrarte a ti mismo.']);
        }
    }

    try {
        $database = new Database();
        $conexion = $database->conectar();

        // Comprobamos si ya existe un participante con el mismo NIF o correo en la misma modalidad
        $sql = 
            "SELECT 1 
            FROM participantes 
            WHERE 
                modalidad = :modalidad 
                AND (nif = :nif OR email = :email) 
            LIMIT 1
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':modalidad', $modalidad, PDO::PARAM_STR);
        $stmt->bindValue(':nif', $nif, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetchColumn()) {
            enviar_respuesta(409, ['ok' => false, 'mensaje' => 'Ya existe un registro con ese NIF o correo en la misma modalidad.']);
        }

        $sql = "INSERT INTO participantes (
                nif,
                nombre,
                apellidos,
                edad,
                domicilio,
                poblacion,
                provincia,
                codigo_postal,
                telefono,
                email,
                modalidad
            ) VALUES (
                :nif,
                :nombre,
                :apellidos,
                :edad,
                :domicilio,
                :poblacion,
                :provincia,
                :codigo_postal,
                :telefono,
                :email,
                :modalidad
            )";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':nif', $nif, PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
        $stmt->bindValue(':edad', $edad, PDO::PARAM_INT);
        $stmt->bindValue(':domicilio', $domicilio, PDO::PARAM_STR);
        $stmt->bindValue(':poblacion', $poblacion, PDO::PARAM_STR);
        $stmt->bindValue(':provincia', $provincia, PDO::PARAM_STR);
        $stmt->bindValue(':codigo_postal', $codigoPostal, PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $telefono, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':modalidad', $modalidad, PDO::PARAM_STR);
        $stmt->execute();

        enviar_respuesta(201, ['ok' => true, 'mensaje' => 'El participante se ha registrado correctamente.']);
    } catch (Throwable $e) {
        if ($e instanceof PDOException && $e->getCode() === '23000') {
            enviar_respuesta(409, ['ok' => false, 'mensaje' => 'Ya existe un participante con ese NIF.']);
        }

        error_log('Error al guardar participante: ' . $e->getMessage());

        enviar_respuesta(500, ['ok' => false, 'mensaje' => 'No se ha podido guardar el participante.']);
    }
?>
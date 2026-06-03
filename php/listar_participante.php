<?php
    require_once './conexion.php';
    require_once './sesion.php';
    header('Content-Type: application/json; charset=utf-8');

    function enviar_respuesta(int $codigo, array $datos) {
        http_response_code($codigo);
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!usuarioAutenticado()) {
        enviar_respuesta(401, ['ok' => false, 'mensaje' => 'Debes iniciar sesión para ver el listado.']);
    }

    try {
        $busqueda = trim((string) filter_input(INPUT_GET, 'q', FILTER_UNSAFE_RAW));

        $database = new Database();
        $conexion = $database->conectar();

        $sql = "SELECT *
            FROM participantes";

        if ($busqueda !== '') {
            $sql .= " WHERE nif LIKE :busqueda OR nombre LIKE :busqueda OR apellidos LIKE :busqueda";
        }

        $sql .= ' ORDER BY id DESC';
        $stmt = $conexion->prepare($sql);

        if ($busqueda !== '') {
            $busqueda = $busqueda . '%';
            $stmt->bindValue(':busqueda', $busqueda, PDO::PARAM_STR);
        }

        $stmt->execute();

        enviar_respuesta(200, [
            'ok' => true,
            'participantes' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        ]);
    } catch (Throwable $e) {
        error_log('Error al listar participantes: ' . $e->getMessage());

        enviar_respuesta(500, [
            'ok' => false,
            'mensaje' => 'No se ha podido cargar el listado de participantes.',
        ]);
    }
?>
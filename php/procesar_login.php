    <?php
    require_once './conexion.php';
    require_once './sesion.php';

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Location: ../login.php');
        exit;
    }

    $correo = trim((string) ($_POST['email'] ?? ''));
    $passwd = (string) ($_POST['passwd'] ?? '');

    if ($correo === '' || $passwd === '') {
        $_SESSION['error'] = 'Los campos no pueden estar vacíos.';
        header('Location: ../login.php');
        exit;
    }

    try {
        $database = new Database();
        $conexion = $database->conectar();

        $sql = 
            "SELECT 
                u.id_usuario, 
                u.nombre, 
                u.apellido1, 
                u.apellido2, 
                u.email, 
                u.localidad, 
                u.fecha_nacimiento, 
                u.rol_id, 
                r.nombre AS rol_nombre, 
                c.contrasenna_hash
            FROM usuarios u
            INNER JOIN contrasenas c ON u.id_usuario = c.id_usuario
            INNER JOIN roles r ON u.rol_id = r.id_rol
            WHERE u.email = :correo
            LIMIT 1
        ";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($passwd, $usuario['contrasenna_hash'])) {
            $_SESSION['error'] = 'Credenciales incorrectas.';
            header('Location: ../login.php');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id_usuario' => (int) $usuario['id_usuario'],
            'nombre' => $usuario['nombre'],
            'apellido1' => $usuario['apellido1'],
            'apellido2' => $usuario['apellido2'],
            'email' => $usuario['email'],
            'localidad' => $usuario['localidad'],
            'fecha_nacimiento' => $usuario['fecha_nacimiento'],
            'edad' => fechaNacimientoAEdad($usuario['fecha_nacimiento']),
            'rol_id' => (int) $usuario['rol_id'],
            'rol_nombre' => $usuario['rol_nombre'],
        ];

        header('Location: ../index.php');
        exit;
    } catch (Throwable $e) {
        error_log('Error al procesar login: ' . $e->getMessage());
        $_SESSION['error'] = 'No se ha podido iniciar sesión.';
        header('Location: ../login.php');
        exit;
    }
?>
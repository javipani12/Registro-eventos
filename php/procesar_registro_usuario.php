<?php
    require_once './conexion.php';
    require_once './sesion.php';

    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Location: ../register.php');
        exit;
    }

    $nombre = trim((string) ($_POST['nombre'] ?? ''));
    $apellido1 = trim((string) ($_POST['apellido1'] ?? ''));
    $apellido2 = trim((string) ($_POST['apellido2'] ?? ''));
    $localidad = trim((string) ($_POST['localidad'] ?? ''));
    $fechaNacimiento = trim((string) ($_POST['fecha_nacimiento'] ?? ''));
    $correo = trim((string) ($_POST['correo'] ?? ''));
    $contrasena = (string) ($_POST['contrasena'] ?? '');
    $contrasena2 = (string) ($_POST['contrasena2'] ?? '');

    if ($nombre === '' || $apellido1 === '' || $correo === '' || $contrasena === '' || $contrasena2 === '') {
        $_SESSION['error'] = 'Los campos obligatorios no pueden estar vacíos.';
        header('Location: ../register.php');
        exit;
    }

    if ($fechaNacimiento !== '') {
        $fechaObjeto = DateTimeImmutable::createFromFormat('Y-m-d', $fechaNacimiento);
        $fechaValida = $fechaObjeto && $fechaObjeto->format('Y-m-d') === $fechaNacimiento;

        if (!$fechaValida) {
            $_SESSION['error'] = 'La fecha de nacimiento no es válida.';
            header('Location: ../register.php');
            exit;
        }
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || !preg_match('/^[A-Za-z0-9._%+-]+@educarex\.es$/i', $correo)) {
        error_log('Registro usuario: correo inválido: ' . $correo);
        $_SESSION['error'] = 'El correo electrónico no es válido o debe pertenecer al dominio educarex.es.';
        header('Location: ../register.php');
        exit;
    }

    if (strlen($contrasena) < 8) {
        $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres.';
        header('Location: ../register.php');
        exit;
    }

    if ($contrasena !== $contrasena2) {
        $_SESSION['error'] = 'Las contraseñas no coinciden.';
        header('Location: ../register.php');
        exit;
    }

    try {
        $database = new Database();
        $conexion = $database->conectar();

        $sql = "SELECT 1 FROM usuarios WHERE email = :correo LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':correo', $correo, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetchColumn()) {
            $_SESSION['error'] = 'Ya existe un usuario registrado con ese correo electrónico.';
            header('Location: ../register.php');
            exit;
        }

        $sql = 
            "INSERT INTO usuarios (
                nombre, 
                apellido1, 
                apellido2, 
                email, 
                localidad, 
                fecha_nacimiento, 
                rol_id
            )
            VALUES (
                :nombre, 
                :apellido1,
                :apellido2, 
                :email, 
                :localidad, 
                :fecha_nacimiento, 
                :rol_id
            )
        ";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':apellido1', $apellido1, PDO::PARAM_STR);
        $stmt->bindValue(':apellido2', $apellido2 !== '' ? $apellido2 : null, PDO::PARAM_STR);
        $stmt->bindValue(':email', $correo, PDO::PARAM_STR);
        $stmt->bindValue(':localidad', $localidad !== '' ? $localidad : null, PDO::PARAM_STR);
        $stmt->bindValue(':fecha_nacimiento', $fechaNacimiento !== '' ? $fechaNacimiento : null, PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', 2, PDO::PARAM_INT);
        $stmt->execute();

        $idUsuario = (int) $conexion->lastInsertId();
        $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = 
            "INSERT INTO contrasenas (
                id_usuario, 
                contrasenna_hash
            )
            VALUES (
                :id_usuario, 
                :contrasenna_hash
            )
        ";

        $stmt = $conexion->prepare($sql);
        $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindValue(':contrasenna_hash', $hashContrasena, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['correcto'] = 'Registro exitoso. Ya puedes iniciar sesión.';
        header('Location: ./../login.php');
        exit;
    } catch (Throwable $e) {
        error_log('Error al registrar usuario: ' . $e->getMessage());
        $_SESSION['error'] = 'No se ha podido completar el registro.';
        header('Location: ./../register.php');
        exit;
    }
?>
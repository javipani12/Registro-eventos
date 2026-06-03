<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    function usuarioAutenticado() {
        return isset($_SESSION['usuario']);
    }

    function obtenerUsuarioSesion() {
        return usuarioAutenticado() ? $_SESSION['usuario'] : null;
    }

    function esAdmin() {
        return usuarioAutenticado() && (int)($_SESSION['usuario']['rol_id'] ?? 0) === 1;
    }

    function esUsuarioNormal() {
        return usuarioAutenticado() && (int)($_SESSION['usuario']['rol_id'] ?? 0) === 2;
    }

    function redirigirSiNoAutenticado(string $mensaje = 'Debes iniciar sesión para acceder a esta página.') {
        if (!usuarioAutenticado()) {
            $_SESSION['error'] = $mensaje;
            header('Location: ./login.php');
            exit;
        }
    }

    function fechaNacimientoAEdad(?string $fechaNacimiento) {
        if ($fechaNacimiento === null || $fechaNacimiento === '') {
            return null;
        }

        try {
            $nacimiento = new DateTimeImmutable($fechaNacimiento);
            $hoy = new DateTimeImmutable('today');
            return (int) $hoy->diff($nacimiento)->y;
        } catch (Throwable $e) {
            return null;
        }
    }
?>
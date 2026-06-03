<?php
    require_once './sesion.php';

    /*
        Cierra la sesión del usuario de forma segura, eliminando toda la información de sesión
        tanto en el servidor como en el cliente, y redirige al inicio.
    */
    $_SESSION = [];

    // Eliminar la cookie PHPSESSID del navegador del usuario.
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 3600,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    // Destruir la sesión en el servidor.
    session_destroy();

    // Redirigir al inicio del proyecto actual.
    header('Location: ../index.php');
    exit;
?>
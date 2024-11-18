<?php

// Iniciar la sesión
// Al inicio de cada página protegida, verifica la expiración de sesión
session_start(); // Iniciar la sesión para verificar



if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > 1800) { // si han pasado mas de 1800 segundos desde la ultima conexion, se destruye la sesion
        // Si ha pasado más tiempo que el permitido, destruir la sesión
        session_unset(); // Limpiar variables de sesión
        session_destroy(); // Destruir la sesión
        header("Location: logout.php"); // Redirigir a la página de cierre de sesión
        exit();
    }

    // Actualizar el tiempo de última actividad
    $_SESSION['LAST_ACTIVITY'] = time();
}

if (isset($_SESSION['role'])) {
if ($_SESSION['role'] != "user") {
        header('Location: index.php');
        exit(); // Asegúrate de llamar a exit después de redirigir
    }
}

/* temporal para eliminar sesion hasta que se haga un dashboard correspondiente*/
header("Location: logout.php"); // Redirigir a la página de cierre de sesión
exit();

?>
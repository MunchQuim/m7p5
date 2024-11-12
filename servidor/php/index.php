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


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <link rel="stylesheet" href="../../Cliente/index.css">
    <script src="../js/indexForm.js" defer></script>
</head>

<body>
    <header>
        <div class="header-content">
            <?php
            if (isset($_SESSION['username'])) {
                echo "<h1> Bienvenido, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
                echo '<nav>
                    <a href="logout.php" id="logout-button">Cerrar sesión</a>
                  </nav>';
            } else {
                echo "<h1> Bienvenido! </h1>";
                echo '<nav><a href="#" id="crear-sesion-link">Crear sesión</a>
                 <a href="#" id="iniciar-sesion-link">Iniciar sesión</a></nav>';
            }
            ?>

        </div>
    </header>
    <main>
        <div id="form-container" class="hidden">
        </div>
    </main>
</body>
<script>
    const isLoggedIn = <?php echo json_encode(isset($_SESSION['username'])); ?>;

    /*  window.addEventListener('beforeunload', function () {
         // Envia una solicitud al servidor para cerrar la sesión
         navigator.sendBeacon('logout.php');
     }); */
</script>


</html>
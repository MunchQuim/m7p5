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
    if ($_SESSION['role'] != "admin") {
        // Redireccionar a index.php
        header('Location: index.php');
        exit(); // Asegúrate de llamar a exit después de redirigir
    }
}




?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>index</title>
    <link rel="stylesheet" href="../../Cliente/index2.css">
    <script src="../js/indexForm.js" defer></script>
    <script src="../js/userManagement.js" defer></script>
</head>

<body>
    <header>
        <div class="header-content">
            <?php
            if (isset($_SESSION['username'])) {
                echo "<h1> Bienvenido, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
                echo '<nav>
                    <a href="user.php" id="logout-button">Gestion Usuarios</a>
                    <a href="logout.php" id="logout-button">Cerrar sesión</a>
                  </nav>';
            } else {
                header('Location: index.php');
                exit(); // Asegúrate de llamar a exit después de redirigir
            }
            ?>


        </div>
    </header>
    <main>
        <!-- lista usuarios -->
        <div id="listaUsuarios">

        </div>
        <div id="form-container" class="hidden">
            <form id="form" action="update.php" method="put">
                <label for="username">Username:</label><br>
                <input id="username" type="text" name="username"><br>

                <label for="mail">Email:</label><br>
                <input id="mail" type="text" name="mail">

                <label for="role">Rol:</label><br>
                <select id="role" name="role">
                    <option value="admin">admin</option>
                    <option value="user">user</option>
                </select>

                <label for="emotion">emoción:</label><br>
                <select id="emotion" name="emotion">
                    <option value="Energico">Energico</option>
                    <option value="Triste">Triste</option>
                    <option value="Feliz">Feliz</option>
                    <option value="Relajado">Relajado</option>
                    <option value="Estresado">Estresado</option>
                    <option value="Inspirado">Inspirado</option>
                </select>
                <button type="submit" id="submitBtn">Enviar</button>
            </form>
        </div>

    </main>
</body>
<script>

</script>


</html>
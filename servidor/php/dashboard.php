<?php
include("update.php");
include("read.php");
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
    if ($_SESSION['role'] != "user" &&  $_SESSION['role'] != "admin") {
        // Redireccionar a index.php
        header('Location: index.php');
        exit(); // Asegúrate de llamar a exit después de redirigir
    }
}

    $usuario;
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
        <!-- cuerpo -->
        <h2>Selecciona tu estado de ánimo</h2>
        <form method="POST">
            <div class="dropdown">
                <label for="mood">Estado de ánimo:</label>
                <select name="mood" id="mood">
                    <option value="feliz">Feliz</option>
                    <option value="triste">Triste</option>
                    <option value="relajado">Relajado</option>
                    <option value="energético">Energético</option>
                    <option value="energético">Estresado</option>
                    <option value="energético">Inspirado</option>

                </select>
            </div>
            <button type="submit">Actualizar estado de ánimo</button>
        </form>
      <?php $usuario = json_decode( getUserById($_SESSION["id"]),true) ?><!-- Ahora mismo getUserById no nos da un usuario -->
        <!-- 2. hacer una consulta read.php a la funcion que corresponda para obtener todos los datos -->
        <!-- 3. darle una acción al formulario a update.php enviandole los datos que emos recibido del read mas la emocion que hemos escojido -->
         <?php updateUser($usuario["id"],  $usuario["username"], newPassword:$usuario["password"], $usuario["mail"], $usuario["role"], $newEmotions)   ?>
    </main>
</body>


<script>
    function handleSubmit(event) {
        event.preventDefault(); // Evitar el envío predeterminado del formulario
        const emotion = document.getElementById("emotion").value;
        console.log("Estado de ánimo seleccionado:", emotion);
        // Aquí puedes llamar a tu función, como updateUser
        updateUser(1, 'userName', 'password', 'mail@example.com', 'user', emotion);
    }

    function updateUser(id, username, password, mail, role, emotion) {
        console.log(`Datos actualizados:
            ID: ${id}
            Nombre: ${username}
            Contraseña: ${password}
            Correo: ${mail}
            Rol: ${role}
            Estado de ánimo: ${emotion}`);
    }
</script>


</html>
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

if (isset($_SESSION['emotion'])) {
    $emotion = $_SESSION['emotion'];

} else {
    /*     header('Location: dashboard.php');
        exit(); //redirijo al dashboard en caso de no haber escogido su estado de animo */
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="../../cliente/howler.css">
    <!-- <script src="backColorScript.js" defer></script> -->
    <script src="../js/equilizer.js" defer></script>
    <!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/howler@2.2.4/dist/howler.min.js"></script>
</head>

<body>
<?php
            if (isset($_SESSION['username'])) {
                echo "<h1 id='title'> Bienvenido a Howler.js, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
                echo '<nav>
                    <a href="logout.php" id="logout-button">Cerrar sesión</a>
                  </nav>';
            } else {
                header('Location: index.php');
                exit();
            }
            ?>
    <main>
        <div id="songContainer">
            <div id="imgCanContainer">
                <div id="imgContainer">
                </div>
                <div id="canvasContainer">
                    <canvas id="equalizer" width="600" height="600"></canvas>
                </div>
            </div>

            <div id="optionsContainer" class="mostrado">
                <!--                 <input type="range" id="volumen">
                <input type="range" id="seconds" value=0> -->
            </div>
        </div>
        <div id="side">
            <div id="sideHeader">
            </div>
            <div id="sideMain">
                <div id="songList" class="mostrado"></div>
            </div>
            <div id="sideFooter"></div>
        </div>
    </main>
    <div id="social-media">
        <a href="https://www.linkedin.com/in/joaquimpineda" target="_blank"><img src="../imgs//linkedin.png"
                alt="linkedin"></a>
        <a href="https://github.com/MunchQuim?tab=repositories" target="_blank"><img src="../imgs//github.png"
                alt="linkedin"></a>
    </div>

</body>
<script>
    let animo = <?php echo json_encode($emotion); ?>;
    console.log(animo);


</script>

</html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['username']) && !empty($_POST['username']) &&
        isset($_POST['password']) && !empty($_POST['password'])
    ) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Ruta al archivo JSON que contiene los usuarios
        $json = '../data/users.json';
        $jsonContent = file_get_contents($json);
        // Decodificar el contenido JSON
        $usuarios = json_decode($jsonContent, true);

        // llamar al crud, read(username, password) y si hay devolucion


        // Comprobar si hay devolucion
        if (!empty($response)) {
            // Obtener el primer usuario encontrado (asumiendo que los usernames son únicos)
            $usuarioEncontrado = reset($response); // equivalente a $response[0]
            // Iniciar sesión 
            session_regenerate_id(true);
            session_start();
            $session_timeout = 1800;
            $_SESSION['username'] = $usuarioEncontrado['username'];
            $_SESSION['id'] = $usuarioEncontrado['id'];
            $_SESSION['role'] = $usuarioEncontrado['role'];
            $_SESSION['LAST_ACTIVITY'] = time();

            // Redireccionar a index.php
            header('Location: index.php');
            exit(); // Asegúrate de llamar a exit después de redirigir
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
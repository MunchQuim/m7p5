<?php
include('read.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (
        isset($_POST['username']) && !empty($_POST['username']) &&
        isset($_POST['password']) && !empty($_POST['password'])
    ) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Ruta al archivo JSON que contiene los usuarios
        /*$json = '../data/users.json';
        $jsonContent = file_get_contents($json);
        // Decodificar el contenido JSON
        $usuarios = json_decode($jsonContent, true); */

        // llamar al crud, read(username, password) y si hay devolucion
        $response = getUser($username, $password); // por ahora es esta funcion para comprobar que puede iniciar sesion que no tiene la contraseña encriptada
        // Comprobar si hay devolucion
        if (!empty($response)) {
            // Obtener el primer usuario encontrado (asumiendo que los usernames son únicos)
            echo "no estoy vacio";
            // Iniciar sesión 
            echo '' . json_encode($response);
            session_start();
            session_regenerate_id(true);

            $session_timeout = 1800;
            $_SESSION['username'] = $response['username'];
            $_SESSION['id'] = $response['id'];
            $_SESSION['role'] = $response['role'];
            $_SESSION['emotion'] = $response['emotion'];
            $_SESSION['LAST_ACTIVITY'] = time();
            
            // Redireccionar a index.php
            header('Location: index.php');
            exit(); // Asegúrate de llamar a exit después de redirigir
        }
        else { echo "usuario no existente";}

    } else {
       
        echo "no se han recibido los datos";
    }
}

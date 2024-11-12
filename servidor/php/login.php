<!-- provisional, adaptar segun crud -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['username']) && !empty($_POST['username']) &&
        isset($_POST['password']) && !empty($_POST['password'])
    ) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        // Ruta al archivo JSON que contiene los usuarios
        $json = '../jsons/users.json';
        $jsonContent = file_get_contents($json);
        // Decodificar el contenido JSON
        $usuarios = json_decode($jsonContent, true);

        // Filtrar usuarios para buscar el nombre de usuario
        $usuariosFiltrados = array_filter($usuarios, function ($usuario) use ($username) {
            return $usuario['username'] === $username;
        });

        // Comprobar si hay usuarios filtrados
        if (!empty($usuariosFiltrados)) {
            // Obtener el primer usuario encontrado (asumiendo que los usernames son únicos)
            $usuarioEncontrado = reset($usuariosFiltrados);
            if (password_verify($password, $usuarioEncontrado['password'])) {
                // Iniciar sesión 
                session_regenerate_id(true);
                session_start();
                $session_timeout = 1800;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $usuarioEncontrado['role'];
                $_SESSION['LAST_ACTIVITY'] = time();

                // Redireccionar a basico.php
                header('Location: index.php');
                exit(); // Asegúrate de llamar a exit después de redirigir
            } else {
                // La contraseña es incorrecta
                echo "La contraseña es incorrecta.";

            }
        } else {
            // El usuario no existe
            echo "El usuario no existe.";
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
<?php
// función para leer todos los usuarios
function readUsers()
{

    if (!file_exists('../data/users.json')) {
        return [];

    }
    return json_decode(file_get_contents('../data/users.json'), true) ?? [];
}


function getUser($username, $password)
{

    if (!file_exists('../data/users.json')) {
        return [];

    }
    $usuarios = json_decode(file_get_contents('../data/users.json'));//lo convierto a array

    // Filtrar usuarios para buscar el nombre de usuario
    $usuariosFiltrados = array_filter($usuarios, function ($usuario) use ($username) {
        return $usuario['username'] === $username;
    });

    // Comprobar si hay usuarios filtrados
    if (!empty($usuariosFiltrados)) {
        // Obtener el primer usuario encontrado (asumiendo que los usernames son únicos)
        $usuarioEncontrado = reset($usuariosFiltrados);
        if (password_verify($password, $usuarioEncontrado['password'])) {
            return $usuarioEncontrado;
        } else {
            return [];

        }
    } else {
        return [];
    }
}

function getAdminTest($username, $password)
{

    if (!file_exists('../data/users.json')) {
        return [];
    }
    $usuarios = json_decode(file_get_contents('../data/users.json'),true);//lo convierto a array
    // Filtrar usuarios para buscar el nombre de usuario
    $usuariosFiltrados = array_filter($usuarios, function ($usuario) use ($username) {
        
        return $usuario['username'] === $username;
    });
    
    // Comprobar si hay usuarios filtrados
    if (!empty($usuariosFiltrados)) {
        // Obtener el primer usuario encontrado (asumiendo que los usernames son únicos)
        $usuarioEncontrado = reset($usuariosFiltrados);
        if ($password == $usuarioEncontrado['password']) {
            return $usuarioEncontrado;
        } else {
            return [];

        }
    } else {
        return [];
    }


}

/* // Ejemplo de uso:
$users = readUsers();
foreach ($users as $user) {
    echo "ID: " . $user['id'] . " - Username: " . $user['username'] . " - Mail: " . $user['mail'] .
        " - Role: " . $user['role'] . " - Emotions: " . json_encode($user['emotions']) . "<br>";
}
 */
?>
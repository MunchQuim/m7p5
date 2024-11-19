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
    $usuarios = json_decode(file_get_contents('../data/users.json'),true);//lo convierto a array

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

function getUserById($id)
{
    /* debe añadirse seguridad para evitar que cualquiera pueda acceder a este .php */

    if (!file_exists('../data/users.json')) {
        return [];

    }
    $usuarios = json_decode(file_get_contents('../data/users.json'),true);//lo convierto a array

    // Filtrar usuarios para buscar el nombre de usuario
    $usuariosFiltrados = array_filter($usuarios, function ($usuario) use ($id) {
        return $usuario['id'] === $id;
    });

    // Comprobar si hay usuarios filtrados
    if (!empty($usuariosFiltrados)) {
        // Obtener el primer usuario encontrado (asumiendo que los usernames son únicos)
        $usuarioEncontrado = reset($usuariosFiltrados);
        return json_encode($usuarioEncontrado); 
        
    } else {
        return [];
    }
}
function getUserNameById($id)
{
    /* debe añadirse seguridad para evitar que cualquiera pueda acceder a este .php */

    if (!file_exists('../data/users.json')) {
        return [];

    }
    $usuarios = json_decode(file_get_contents('../data/users.json'));//lo convierto a array

    // Filtrar usuarios para buscar el nombre de usuario
    $usuariosFiltrados = array_filter($usuarios, function ($usuario) use ($id) {
        return $usuario['id'] === $id;
    });

    // Comprobar si hay usuarios filtrados
    if (!empty($usuariosFiltrados)) {
        // Obtener el primer usuario encontrado (asumiendo que los usernames son únicos)
        $usuarioEncontrado = reset($usuariosFiltrados);
        return $usuarioEncontrado['username']; //por ahora solo devuelvo el nombre
        
    } else {
        return [];
    }
}
function isUserByName($username)
{
    /* debe añadirse seguridad para evitar que cualquiera pueda acceder a este .php */

    if (!file_exists('../data/users.json')) {
        return null;

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
        return true; //por ahora solo devuelvo el nombre
        
    } else {
        return false;
    }
}

/* // Ejemplo de uso:
$users = readUsers();
foreach ($users as $user) {
    echo "ID: " . $user['id'] . " - Username: " . $user['username'] . " - Mail: " . $user['mail'] .
        " - Role: " . $user['role'] . " - Emotions: " . json_encode($user['emotions']) . "<br>";
}
 */

 function getSongsByEmotion($emotion)
{
    /* debe añadirse seguridad para evitar que cualquiera pueda acceder a este .php */

    if (!file_exists('../data/songs.json')) {
        return [];
    }
    $allSongs = json_decode(file_get_contents('../data/songs.json'), true);//lo convierto a array

    // Filtrar usuarios para buscar el nombre de usuario
    $filteredSongs = [];
    foreach ($allSongs as $songs) {
        if($songs["emotion"] == $emotion){
            $filteredSongs[] = $songs;//añado la song al array;
        }
    };
    return $filteredSongs;  // devuelve todas las caciones con esa emocion 
   
}
if (isset($_GET['emotion'])) {
    
    $emotion = htmlspecialchars($_GET['emotion']);
    // Obtener las canciones para esa emoción
    $songs = getSongsByEmotion($emotion);
   
    // Establecer encabezado de respuesta para indicar que la respuesta es JSON
    header('Content-Type: application/json');

    if (!empty($songs)) {
        echo json_encode($songs);
    } else {
        echo json_encode(['error' => 'No se encontraron canciones para esta emoción']);
    }
} else {
    // Si no se proporciona emoción, responder con un error o con todas las canciones
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Emoción no especificada']);
}

?>
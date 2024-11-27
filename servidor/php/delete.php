<?php
session_start(); // Iniciar la sesión para verificar
if (isset($_SESSION['LAST_ACTIVITY']) && isset($_SESSION['role'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] <= 1800 && $_SESSION['role'] == "admin") { // si han pasado mas de 1800 segundos desde la ultima conexion, se destruye la sesion
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    if(isset($_POST['id'])){
        $id = $_POST['id'];
        deleteUser($id);
    }

}

// función para eliminar un usuario por ID
function deleteUser($id) {
    if (!file_exists('../data/users.json')) {
        echo "El archivo de datos no existe.";
        return;
    }

 $users = json_decode(file_get_contents('../data/users.json'), true) ?? [];
 // Buscar el índice del usuario que queremos eliminar
 $userIndex = array_search($id, array_column($users, 'id'));

 if ($userIndex !== false) { // Si el usuario existe
 unset($users[$userIndex]); // Eliminar el usuario del array
 $users = array_values($users); // Reindexar el array
 // Guardar el array actualizado en el archivo JSON
 file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
 echo "Usuario eliminado."; 
 }
 else {
    echo "Usuario no encontrado.";
}
}


?>
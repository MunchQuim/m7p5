<?php
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
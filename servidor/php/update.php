<?php
// Función para actualizar un usuario en el archivo JSON con dos 
alternativas
function updateUser($id, $newName, $newPassword, $newMail) {
    if (!file_exists('data/users.json')) {
        echo "El archivo de datos no existe.";
        return;
    }


 $users = json_decode(file_get_contents('data/users.json'), true) ??[];
 

 // Opción 1: Usando array_column y array_search para encontrar el índice del usuario
 $userIndex = array_search($id, array_column($users, 'id'));
 if ($userIndex !== false) { // Si el usuario existe
 $users[$userIndex]['name'] = $newName; 
 $users[$userIndex]['password'] = $newPassword;
 $users[$userIndex]['email'] = $newEmail; 
 // Guardar los cambios en el archivo JSON
 file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));
 echo "Usuario actualizado."; 
 } else { 
 echo "Usuario no encontrado."; 
 }
 /**************************************************************
 //Opción 2: Usando array_key_exists y array_search
 foreach ($users as $index => $user) {
 if (array_key_exists('id', $user) && $user['id'] === $id) { 
 // Si el usuario con el ID existe
 $users[$index]['name'] = $newName;
 $users[$index]['email'] = $newEmail;
 
 // Guardar los cambios en el archivo JSON
 file_put_contents('data/users.json', json_encode($users, 
JSON_PRETTY_PRINT));
 echo "Usuario actualizado.";
 return; // Finalizar la función después de actualizar
 }
 }
 echo "Usuario no encontrado.";
 ****************************************************************/
} 
// Ejemplo de uso para las dos alternativas:
updateUser("ID_DEL_USUARIO", "NuevoUsuario", "nuevaContraseña", "nuevoemail@example.com");
 ?>
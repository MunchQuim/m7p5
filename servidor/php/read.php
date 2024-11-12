<?php
// función para leer todos los usuarios
function readUsers() {
    return json_decode(file_get_contents('data/users.json'), true) ?? [];
   } 
   // Ejemplo de uso:
   $users = readUsers();
   foreach ($users as $user) {
    echo "ID: " . $user['id'] . " - Nombre: " . $user['name'] . " - 
   Email: " . $user['email'] . "<br>"; 
   } 

?>
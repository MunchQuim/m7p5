<?php
// función para leer todos los usuarios
function readUsers() {

if (!file_exists('data/users.json')){
return[];

}


    return json_decode(file_get_contents('data/users.json'), true) ?? [];
   } 
   // Ejemplo de uso:
   $users = readUsers();
   foreach ($users as $user) {
    echo "ID: " . $user['id'] . " - Username: " . $user['username'] . " - Mail: " . $user['mail'] . 
         " - Role: " . $user['role'] . " - Emotions: " . json_encode($user['emotions']) . "<br>"; 
}

?>
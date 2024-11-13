<?php
function createUser($name, $password, $email) {
    $users = json_decode(file_get_contents('../data/users.json'), true) ?? [];
    
    $newUser = [ 
    "id" => uniqid(),
    "name" => $name, 
    "password" => $password,
    "mail" => $email, 
    "role" => "user",
    "emotion" => []
    ];
    $users[] = $newUser; // Agregar el nuevo usuario a la lista
    
    file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT)); // Guardar la lista actualizada
    return $newUser; 
   } 

?>
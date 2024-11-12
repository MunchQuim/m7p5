<?php
function createUser($name, $email) {
    $users = json_decode(file_get_contents('data/users.json'), true) ?? [];
    
    $newUser = [ 
    "id" => uniqid(),
    "name" => $name, 
    "email" => $email, 
    "role" => "user"
    ];
    $users[] = $newUser; // Agregar el nuevo usuario a la lista
    
    file_put_contents('data/users.json', json_encode($users, 
   JSON_PRETTY_PRINT)); // Guardar la lista actualizada
    return $newUser; 
   } 
   // Ejemplo de uso:
   $newUser = createUser("Juan", "juan@example.com");
   echo "Usuario creado: " . json_encode($newUser);
?>
<?php

include("read.php");
// Función para actualizar un usuario en el archivo JSON con dos alternativas
if(isset($_POST["mood"])) {
    $mood = $_POST["mood"];
    session_start();
    
    updateMoodById($_SESSION['id'],$mood);
    $_SESSION['emotion'] = $mood;
    header('Location: equalizer.php');
    exit();
    
}
function updateMoodById($id, $newEmotions) {
    if (!file_exists('../data/users.json')) {
        echo "El archivo de datos no existe.";
        return;
    }


 $users = json_decode(file_get_contents('../data/users.json'), true) ??[];
 
 
 // Opción 1: Usando array_column y array_search para encontrar el índice del usuario
 $userIndex = array_search($id, array_column($users, 'id'));
 if ($userIndex !== false) { // Si el usuario existe
   
 $users[$userIndex]['emotion'] = $newEmotions; 

 


 // Guardar los cambios en el archivo JSON
 file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
 echo "Usuario actualizado."; 
 } else { 
 echo "Usuario no encontrado."; 
 }

} 


function updateUser($id, $newName, $newPassword, $newMail, $newRole, $newEmotions) {
    if (!file_exists('../data/users.json')) {
        echo "El archivo de datos no existe.";
        return;
    }


 $users = json_decode(file_get_contents('../data/users.json'), true) ??[];
 

 // Opción 1: Usando array_column y array_search para encontrar el índice del usuario
 $userIndex = array_search($id, array_column($users, 'id'));
 if ($userIndex !== false) { // Si el usuario existe
 $users[$userIndex]['username'] = $newName; 
 $users[$userIndex]['password'] = $newPassword;
 $users[$userIndex]['mail'] = $newMail; 
 $users[$userIndex]['role'] = $newRole; 
 $users[$userIndex]['emotion'] = $newEmotions; 

 


 // Guardar los cambios en el archivo JSON
 file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
 echo "Usuario actualizado."; 
 } else { 
 echo "Usuario no encontrado."; 
 }

} 
// Ejemplo de uso para las dos alternativas:

 ?>
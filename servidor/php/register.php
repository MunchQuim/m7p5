<?php
include('read.php');
include('create.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['username']) && !empty($_POST['username']) &&
        isset($_POST['password']) && !empty($_POST['password']) &&
        isset($_POST['confirm-password']) && !empty($_POST['confirm-password']) &&
        isset($_POST['email']) && !empty($_POST['email'])
    ) {

        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $confirmPassword = password_hash($_POST['confirm-password'], PASSWORD_DEFAULT);
        $mail = $_POST['email'];

        if ($_POST['password'] == $_POST['confirm-password']) {
            $response = isUserByName($username); //true user existe, false user no existe, null = error
            if($response === null){
                echo'Error';
            } 
            // Comprobar si hay usuarios filtrados
            elseif (!$response) {
                createUser($username, $password, $mail);
                //e intenta un inicio de sesion

                echo '<form id="autoSubmitForm" action="login.php" method="POST" style="display: none;">
            <input type="text" name="username" value="' . htmlspecialchars($username) . '">
            <input type="password" name="password" value="' . htmlspecialchars($_POST['password']) . '">
          </form>';
                echo '<script>document.getElementById("autoSubmitForm").submit();</script>';

                //

            } else {
                // El usuario yua existe
                echo "El nombre de usuario ya existe.";
            }
        } else {
            echo "Las contraseñas no coinciden";
        }
    } else {
        echo "Por favor, completa todos los campos.";
    }
}
?>
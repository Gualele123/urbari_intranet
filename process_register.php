<?php
include 'config.php';

if (isset($_POST['name'], $_POST['email'], $_POST['pass'], $_POST['cpass'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = md5($_POST['pass']);
    $cpass = md5($_POST['cpass']);
    $user_type = 'user'; // Valor predeterminado para el user_type

    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_img/'.$image;

    $select = $pdo->prepare("SELECT * FROM `users` WHERE name = ?");
    $select->execute([$name]);

    if ($select->rowCount() > 0) {
        echo json_encode(['error' => 'El usuario ya existe!']);
    } else {
        if ($pass != $cpass) {
            echo json_encode(['error' => 'Confirmar contraseña no coincide!']);
        } elseif ($image_size > 2000000) {
            echo json_encode(['error' => 'El tamaño de la imagen es muy grande!']);
        } else {
            $insert = $pdo->prepare("INSERT INTO `users` (name, email, password, user_type, image) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$name, $email, $cpass, $user_type, $image]);

            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Error al registrar el usuario!']);
            }
        }
    }
} else {
    echo json_encode(['error' => 'Datos incompletos!']);
}
?>

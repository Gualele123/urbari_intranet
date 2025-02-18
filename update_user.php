<?php
include 'config.php';

if (isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];

    // Manejo de la imagen
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_img/' . $image;

    if (!empty($image)) {
        if ($image_size > 2000000) {
            echo 'El tamaño de la imagen es muy grande';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $update_user = $pdo->prepare("UPDATE `users` SET name = ?, email = ?, user_type = ?, image = ? WHERE id = ?");
            $update_user->execute([$name, $email, $user_type, $image, $update_id]);
            echo 'Usuario actualizado correctamente';
        }
    } else {
        $update_user = $pdo->prepare("UPDATE `users` SET name = ?, email = ?, user_type = ? WHERE id = ?");
        $update_user->execute([$name, $email, $user_type, $update_id]);
        echo 'Usuario actualizado correctamente';
    }
}
?>

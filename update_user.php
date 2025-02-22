<?php
include 'config.php';

if (isset($_POST['update_id'])) {
    $update_id = $_POST['update_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $rol = $_POST['rol']; // Nombre del rol seleccionado

    // Obtener el rol_id correspondiente al rol seleccionado
    $select_role = $pdo->prepare("SELECT id FROM `roles` WHERE rol = ?");
    $select_role->execute([$rol]);
    $rol_id = $select_role->fetch(PDO::FETCH_ASSOC)['id'];

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
            $update_user = $pdo->prepare("UPDATE `users` SET name = ?, email = ?, rol_id = ?, image = ? WHERE id = ?");
            $update_user->execute([$name, $email, $rol_id, $image, $update_id]);
            echo 'Usuario actualizado correctamente';
        }
    } else {
        $update_user = $pdo->prepare("UPDATE `users` SET name = ?, email = ?, rol_id = ? WHERE id = ?");
        $update_user->execute([$name, $email, $rol_id, $update_id]);
        echo 'Usuario actualizado correctamente';
    }
}
?>

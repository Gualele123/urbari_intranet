<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_id = $_POST['update_id'];
    $rol = filter_var($_POST['rol'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $estado = filter_var($_POST['estado'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $update_role = $pdo->prepare("UPDATE `roles` SET rol = ?, descripcion = ?, estado = ? WHERE id = ?");
    $success = $update_role->execute([$rol, $descripcion, $estado, $update_id]);

    if ($success) {
        echo "Rol actualizado correctamente.";
    } else {
        echo "Error al actualizar el rol.";
    }
} else {
    echo "Método de solicitud no permitido.";
}
?>

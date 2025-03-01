<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $update_id = $_POST['update_id'];
    $rol = $_POST['rol'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    $update_role = $pdo->prepare("UPDATE `roles` SET rol = ?, descripcion = ?, estado = ? WHERE id = ?");
    $update_role->execute([$rol, $descripcion, $estado, $update_id]);

    header('location:admin_roles.php');
    exit;
}
?>

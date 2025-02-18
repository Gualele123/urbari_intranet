<?php
include 'config.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_user = $pdo->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_user->execute([$delete_id]);
    echo 'Usuario eliminado correctamente';
}
?>

<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die('ID no proporcionado');
}

$id = $_GET['id'];

// Eliminar el contacto
$sql = "DELETE FROM contactos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

header('Location: admin_page.php');
exit();
?>

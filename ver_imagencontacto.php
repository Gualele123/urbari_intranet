<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die('ID no proporcionado');
}

$id = $_GET['id'];

// Obtener la foto del contacto
$sql = "SELECT foto FROM contactos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if ($contact) {
    header("Content-Type: image/jpeg");
    echo $contact['foto'];
}
?>

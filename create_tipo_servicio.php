<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_servicio = $_POST['tipo_servicio'];
    $imagen = 'uploads/default_tipo_servicio.jpg'; // Imagen por defecto

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $stmt = $pdo->prepare('INSERT INTO tipo_servicio (imagen, tipo_servicio) VALUES (?, ?)');
    $stmt->execute([$imagen, $tipo_servicio]);

    header('Location: index.php');
}
?>

<form action="create_tipo_servicio.php" method="POST" enctype="multipart/form-data">
    <label for="tipo_servicio">Tipo de Servicio:</label>
    <input type="text" id="tipo_servicio" name="tipo_servicio" required>
    <br>
    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" name="imagen">
    <br>
    <button type="submit">Crear Tipo de Servicio</button>
</form>

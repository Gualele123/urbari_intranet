<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion_corta = $_POST['descripcion_corta'];
    $descripcion = $_POST['descripcion'];
    $imagen = 'uploads/' . basename($_FILES['imagen']['name']);
    
    move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);

    $stmt = $pdo->prepare('INSERT INTO comunicados (titulo, descripcion_corta, descripcion, imagen) VALUES (?, ?, ?, ?)');
    $stmt->execute([$titulo, $descripcion_corta, $descripcion, $imagen]);

    header('Location: admin_page.php');
}
?>

<form action="crearcomunicado.php" method="POST" enctype="multipart/form-data">
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" required>
    <br>
    <label for="descripcion_corta">Descripción Corta:</label>
    <input type="text" id="descripcion_corta" name="descripcion_corta" required>
    <br>
    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required></textarea>
    <br>
    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" name="imagen" required>
    <br>
    <button type="submit">Crear Comunicado</button>
</form>

<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_servicio_id = $_POST['tipo_servicio_id'];
    $servicio = $_POST['servicio'];
    $precio = $_POST['precio'];
    $imagen = 'uploads/default_servicio.jpg'; // Imagen por defecto

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $stmt = $pdo->prepare('INSERT INTO servicios (imagen, tipo_servicio_id, servicio, precio) VALUES (?, ?, ?, ?)');
    $stmt->execute([$imagen, $tipo_servicio_id, $servicio, $precio]);

    header('Location: servicios.php?tipo_servicio_id=' . $tipo_servicio_id);
}

$stmt_tipo_servicio = $pdo->query('SELECT * FROM tipo_servicio');
$tipo_servicios = $stmt_tipo_servicio->fetchAll();
?>

<?php
  include 'header.php';
?>

<?php
  include 'navbar.php';
?>

<form action="create_servicio.php" method="POST" enctype="multipart/form-data">
    <label for="tipo_servicio_id">Tipo de Servicio:</label>
    <select class="form-control" id="tipo_servicio_id" name="tipo_servicio_id">
        <?php foreach ($tipo_servicios as $tipo_servicio): ?>
            <option value="<?php echo $tipo_servicio['id']; ?>"><?php echo $tipo_servicio['tipo_servicio']; ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <label for="servicio">Servicio:</label>
    <input class="form-control" type="text" id="servicio" name="servicio" required>
    <br>
    <label for="precio">Precio:</label>
    <input class="form-control" type="number" step="0.01" id="precio" name="precio" required>
    <br>
    <label for="imagen">Imagen:</label>
    <input class="form-control" type="file" id="imagen" name="imagen">
    <br>
    <button class="btn btn-success" type="submit">Crear Servicio</button>
    
</form>

<?php
  include 'footer.php';
?>

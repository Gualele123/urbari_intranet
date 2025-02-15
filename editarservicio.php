<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['servicio'])) {
    $id = $_POST['id'];
    $tipo_servicio_id = $_POST['tipo_servicio_id'];
    $servicio = $_POST['servicio'];
    $precio = $_POST['precio'];
    $imagen = !empty($_FILES['imagen']['name']) ? 'uploads/' . $_FILES['imagen']['name'] : $_POST['imagen_actual'];

    // Subir la imagen si se proporciona
    if (!empty($_FILES['imagen']['name'])) {
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $stmt = $pdo->prepare("UPDATE servicios SET imagen = ?, tipo_servicio_id = ?, servicio = ?, precio = ? WHERE id = ?");
    $stmt->execute([$imagen, $tipo_servicio_id, $servicio, $precio, $id]);

    // header('Location: servicios.php?tipo_servicio_id=' . $tipo_servicio_id);
    header('Location: admin_page.php');
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM servicios WHERE id = ?");
$stmt->execute([$id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM tipo_servicio");
$tipos_servicio = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Servicio</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $servicio['id'] ?>">
        <input type="hidden" name="imagen_actual" value="<?= $servicio['imagen'] ?>">
        <select name="tipo_servicio_id">
            <?php foreach ($tipos_servicio as $tipo_servicio): ?>
            <option value="<?= $tipo_servicio['id'] ?>" <?= $servicio['tipo_servicio_id'] == $tipo_servicio['id'] ? 'selected' : '' ?>><?= $tipo_servicio['tipo_servicio'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="servicio" value="<?= $servicio['servicio'] ?>" required>
        <input type="number" step="0.01" name="precio" value="<?= $servicio['precio'] ?>" required>
        <input type="file" name="imagen">
        <button type="submit">Actualizar Servicio</button>
    </form>
</body>
</html>

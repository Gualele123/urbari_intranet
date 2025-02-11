<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tipo_servicio'])) {
    $id = $_POST['id'];
    $tipo_servicio = $_POST['tipo_servicio'];
    $imagen = !empty($_FILES['imagen']['name']) ? 'uploads/' . $_FILES['imagen']['name'] : $_POST['imagen_actual'];

    // Subir la imagen si se proporciona
    if (!empty($_FILES['imagen']['name'])) {
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $stmt = $pdo->prepare("UPDATE tipo_servicio SET imagen = ?, tipo_servicio = ? WHERE id = ?");
    $stmt->execute([$imagen, $tipo_servicio, $id]);

    header('Location: admin_page.php');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM tipo_servicio WHERE id = ?");
$stmt->execute([$id]);
$tipo_servicio = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php
  include 'header.php';
?>

<?php
  include 'navbar.php';
?>

<div class="row">
      <div class="col">
      </div>
      <div class="col-6">
      <h1>Editar Tipo de Servicio</h1>
      <form method="post" enctype="multipart/form-data">
        <input class="form-control" type="hidden" name="id" value="<?php echo $tipo_servicio['id'] ?>">
        <input class="form-control" type="hidden" name="imagen_actual" value="<?php echo $tipo_servicio['imagen'] ?>">
        <label for="tiposervicio">Tipo de Servicio:</label>
        <input id="tiposervicio" class="form-control" type="text" name="tipo_servicio" value="<?php echo $tipo_servicio['tipo_servicio'] ?>" required>
        <label for="imagen">Imagen:</label>
        <input id="imagen" class="form-control" type="file" name="imagen">
        <button class="btn btn-success" type="submit">Actualizar</button>
        <a href="admin_page.php" class="btn btn-secondary" type="submit">Cancelar</a>
        
<i class="fa-solid fa-trash"></i>
        </form>
      </div>
      <div class="col">
      </div>
</div>
<?php
  include 'footer.php';
?>

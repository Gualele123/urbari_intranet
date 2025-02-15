<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die('ID no proporcionado');
}

$id = $_GET['id'];

// Obtener los datos del contacto
$sql = "SELECT * FROM contactos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$contact = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$contact) {
    die('Contacto no encontrado');
}

// Obtener todas las áreas para el select
$sql = "SELECT * FROM area";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $id_area = $_POST['id_area'];

    // Verificar si se subió una nueva foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);
        $sql = "UPDATE contactos SET foto = :foto, nombre = :nombre, contacto = :contacto, 
                id_area = :id_area WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['foto' => $foto, 'nombre' => $nombre, 'contacto' => $contacto, 
                        'id_area' => $id_area, 'id' => $id]);
    } else {
        $sql = "UPDATE contactos SET nombre = :nombre, contacto = :contacto, 
                id_area = :id_area WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre, 'contacto' => $contacto, 
                        'id_area' => $id_area, 'id' => $id]);
    }

    header('Location: admin_page.php');
    exit();
}
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
          </div>
          <div class="col">
          </div>
</div>

<div class="row">
      <div class="col">
      </div>
      <div class="col-6">
      <h1>Editar Contacto</h1>
    <form action="editarcontacto.php?id=<?= $contact['id']; ?>" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre del Contacto:</label>
        <input  class="form-control" type="text" id="nombre" name="nombre" value="<?= $contact['nombre']; ?>" required> <br>

        <label for="contacto">Contacto:</label>
        <input  class="form-control" type="text" id="contacto" name="contacto" value="<?= $contact['contacto']; ?>" required> <br>

        <label for="id_area">Área:</label>
        <select  class="form-control" name="id_area" id="id_area" required>
            <?php foreach ($areas as $area): ?>
                <option value="<?= $area['id']; ?>" <?= $area['id'] == $contact['id_area'] ? 'selected' : ''; ?>>
                    <?= $area['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select> <br>

        <label for="foto">Foto (deja en blanco para mantener la actual):</label>
        <input  class="form-control" type="file" id="foto" name="foto" accept="image/*"> <br>

        <button class="btn btn-primary" type="submit">Actualizar</button>
        <a class="btn btn-secondary" href="admin_page.php">Volver</a>
    </form>
      </div>
      <div class="col">
      </div>
</div>

<div class="row">
      <div class="col">
      </div>
      <div class="col-6">
      </div>
      <div class="col">
      </div>
</div>


<?php
  include 'footer.php';
?>

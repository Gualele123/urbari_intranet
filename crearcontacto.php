<?php
include 'config.php';

// Obtener todas las áreas para el select
$sql = "SELECT * FROM area";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $contacto = $_POST['contacto'];
    $id_area = $_POST['id_area'];

    // Verificar si se subió una foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = file_get_contents($_FILES['foto']['tmp_name']);  // Leer la imagen como binario

        // Insertar los datos en la base de datos
        $sql = "INSERT INTO contactos (foto, nombre, contacto, id_area) 
                VALUES (:foto, :nombre, :contacto, :id_area)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['foto' => $foto, 'nombre' => $nombre, 'contacto' => $contacto, 'id_area' => $id_area]);

        header('Location: admin_page.php');
        exit();
    } else {
        echo "Por favor selecciona una foto.";
    }
}
?>


<?php
  include 'header.php';
?>

<?php
  include 'navbar.php';
?>


<div class="container">
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
                    <h1>Agregar Nuevo Contacto</h1>
                    <form action="crearcontacto.php" method="POST" enctype="multipart/form-data">
                        <label for="nombre">Nombre del Contacto:</label>
                        <input class="form-control" type="text" id="nombre" name="nombre" required><br><br>

                        <label for="contacto">Contacto:</label>
                        <input class="form-control" type="text" id="contacto" name="contacto" required><br><br>

                        <label for="id_area">Área:</label>
                        <select class="form-control" name="id_area" id="id_area" required>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?= $area['id']; ?>"><?= $area['nombre']; ?></option>
                            <?php endforeach; ?>
                        </select><br><br>
                            
                        <label for="foto">Foto:</label>
                        <input class="form-control" type="file" id="foto" name="foto" accept="image/*" required><br><br>
                            
                        <button class="btn btn-success" type="submit">Guardar</button>
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

</div>
                    



<?php
  include 'footer.php';
?>
    

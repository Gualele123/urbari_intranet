<?php

require 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['servicio'])) {
  $tipo_servicio_id = $_POST['tipo_servicio_id'];
  $servicio = $_POST['servicio'];
  $precio = $_POST['precio'];
  $imagen = !empty($_FILES['imagen']['name']) ? 'ruta/de/la/imagen/' . $_FILES['imagen']['name'] : 'ruta/imagen/por/defecto.jpg';

  // Subir la imagen si se proporciona
  if (!empty($_FILES['imagen']['name'])) {
      move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
  }

  $stmt = $pdo->prepare("INSERT INTO servicio (imagen, tipo_servicio_id, servicio, precio) VALUES (?, ?, ?, ?)");
  $stmt->execute([$imagen, $tipo_servicio_id, $servicio, $precio]);
}

$stmt = $pdo->query("SELECT * FROM tipo_servicio");
$tipos_servicio = $stmt->fetchAll(PDO::FETCH_ASSOC);


$tipo_servicio_id = $_GET['tipo_servicio_id'];

$stmt = $pdo->prepare("SELECT * FROM servicios WHERE tipo_servicio_id = ?");
$stmt->execute([$tipo_servicio_id]);
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

          <div class="col-10">
              <a class="btn btn-success" href="create_servicio.php?tipo_servicio_id=<?php echo $tipo_servicio_id; ?>">Agregar Servicio</a>
              <a class="btn btn-secondary" href="admin_page.php">Volver</a>
              <br>
              <h1>Lista de Servicios</h1>
              <table id="myTable" class="table table-striped table-bordered table-sm" >
                  <thead>
                    <tr>
                      <th>Imagen</th>
                      <th>Servicio</th>
                      <th>Precio</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($servicios as $servicio): ?>
                      <tr>
                              <td><img src="<?php echo $servicio['imagen'] ?>" alt="<?php echo $servicio['servicio'] ?>" width="80"></td>
                              <td><?php echo $servicio['servicio'] ?></td>
                              <td><?php echo $servicio['precio'] ?></td>
                              <td>
                                  <a class="btn btn-secondary" href="editarservicio.php?id=<?php echo $servicio['id'] ?>&tipo_servicio_id=<?php echo $tipo_servicio_id ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                  <a class="btn btn-danger" href="eliminarservicio.php?id=<?php echo $servicio['id'] ?>&tipo_servicio_id=<?php echo $tipo_servicio_id ?>"><i class="fa-solid fa-trash"></i></a>
                              </td>
                      </tr>
                  <?php endforeach; ?>
                  </tbody>
              </table>
          </div>

          <div class="col">
          </div>
</div>

<?php
  include 'footer.php';
?>


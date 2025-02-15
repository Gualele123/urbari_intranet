<?php
require 'config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM comunicados WHERE id = ?');
$stmt->execute([$id]);
$comunicado = $stmt->fetch();
?>

<?php
  include 'header.php';
?>

<?php
  include 'navbar.php';
?>

<div class="detalle-contenedor">
    <div class="titulo"><h2><?php echo $comunicado['titulo']; ?></h2></div>
    <div class="img">
        <img src="<?php echo $comunicado['imagen']; ?>" alt="Imagen del Comunicado" >
    </div>
    <div class="fecha">
        <p><strong>Fecha de Publicacion: </strong><?php echo $comunicado['fecha']; ?></p>
        <p><strong>Autor: </strong><?php echo $comunicado['autor']; ?></p>
    </div>
    <div class="descripcion">
        <p><?php echo $comunicado['descripcion']; ?></p>
    </div>
    <div class="btn"><a class="btn btn-secondary" href="./admin_page.php">Volver</a></div>
</div>



<?php
  include 'footer.php';
?>

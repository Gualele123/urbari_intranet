<?php
require 'config.php';

$stmt = $pdo->query('SELECT * FROM tipo_servicio');
$tipo_servicios = $stmt->fetchAll();
?>

<?php foreach ($tipo_servicios as $tipo_servicio): ?>
    <div class="card">
        <img src="<?php echo $tipo_servicio['imagen']; ?>" alt="Imagen del Tipo de Servicio">
        <h2><?php echo $tipo_servicio['tipo_servicio']; ?></h2>
        <a href="servicios.php?tipo_servicio_id=<?php echo $tipo_servicio['id']; ?>">Ver Servicios</a>
    </div>
<?php endforeach; ?>

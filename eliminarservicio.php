<?php
require 'config.php';

$id = $_GET['id'];
$tipo_servicio_id = $_GET['tipo_servicio_id'];
$stmt = $pdo->prepare("DELETE FROM servicio WHERE id = ?");
$stmt->execute([$id]);

header('Location: servicios.php?tipo_servicio_id=' . $tipo_servicio_id);
?>
<?php
require 'config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM tipo_servicio WHERE id = ?");
$stmt->execute([$id]);

header('Location: admin_page.php');
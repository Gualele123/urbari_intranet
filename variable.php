<?php
include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el token de sesión de la URL coincide con el de la sesión
if (!isset($_GET['session_token']) || $_GET['session_token'] !== $_SESSION['session_token']) {
    header('location:index.php');
    exit;
}

// Aquí va el resto de tu código para procesar el parámetro 'id' y mostrar los detalles del comunicado
$id = $_GET['id'];
$select_comunicado = $pdo->prepare("SELECT * FROM `comunicados` WHERE id = ?");
$select_comunicado->execute([$id]);
$comunicado = $select_comunicado->fetch(PDO::FETCH_ASSOC);

// Mostrar detalles del comunicado
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Comunicado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="contenido">
        <h2><?= $comunicado['titulo']; ?></h2>
        <p><?= $comunicado['contenido']; ?></p>
        <!-- Otros detalles del comunicado -->
    </div>
</body>
</html>

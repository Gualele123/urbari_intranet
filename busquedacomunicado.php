<?php
require 'config.php';

$perPage = 6; // Cantidad de comunicados por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
$search = $_GET['search'];
$stmt = $pdo->prepare("SELECT * FROM comunicados WHERE titulo LIKE ? OR descripcion LIKE ? OR fecha LIKE ? OR autor LIKE ? LIMIT {$start}, {$perPage}");
$stmt->execute(["%$search%", "%$search%", "%$search%", "%$search%"]);
$comunicados = $stmt->fetchAll();

foreach ($comunicados as $comunicado) {
    echo '<div class="card">';
        echo '<div class="img-contenedor">';
            echo '<img class="card-img-top" src="' . $comunicado['imagen'] . '" alt="Imagen del Comunicado">';
        echo '</div>';
        echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $comunicado['titulo'] . '</h5>';
            echo '<p class="card-text">' . substr($comunicado['descripcion'], 0, 30) . '...</p>';
            echo '<p class="fecha">' . $comunicado['fecha'] . '</p>';
            // echo '<p>' . $comunicado['autor'] . '</p>';
            echo '<a class="btn btn-primary" href="detallecomunicado.php?id=' . $comunicado['id'] . '">Ver más</a>';
        echo '</div>';
    echo '</div>';
}

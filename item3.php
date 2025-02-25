<?php
require 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id']; // Suponiendo que ya tenemos el rol_id en la sesión

// Obtener los permisos del rol del usuario
$select_permissions = $pdo->prepare("SELECT modulo, permiso, valor FROM `roles_permisos` JOIN `permisos` ON roles_permisos.id_permiso = permisos.id WHERE id_rol = ? AND modulo = 'comunicados'");
$select_permissions->execute([$role_id]);
$permissions = $select_permissions->fetchAll(PDO::FETCH_ASSOC);

$allowed_permissions = [];
foreach ($permissions as $permission) {
    if ($permission['valor'] == 1) {
        $allowed_permissions[$permission['permiso']] = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($allowed_permissions['crear'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    date_default_timezone_set('America/La_Paz');
    $fecha = date('Y-m-d H:i:s', time());
    // $fecha = date('Y-m-d H:i:s', time() - 14400); // Hora de Bolivia (UTC-4)
    $autor = $_POST['autor'];
    $imagen = 'uploads/default.jpg'; // Imagen por defecto

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $stmt = $pdo->prepare('INSERT INTO comunicados (titulo, descripcion, fecha, autor, imagen) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$titulo, $descripcion, $fecha, $autor, $imagen]);

    header('Location: admin_page.php');
}

// ver comunicados
$perPage = 6; // Cantidad de comunicados por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

$search = isset($_GET['search']) ? $_GET['search'] : '';

$stmt = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM comunicados WHERE titulo LIKE ? OR descripcion LIKE ? LIMIT {$start}, {$perPage}");
$stmt->execute(["%$search%", "%$search%"]);
$comunicados = $stmt->fetchAll();

$total = $pdo->query("SELECT FOUND_ROWS() as total")->fetch()['total'];
$pages = ceil($total / $perPage);
?>

<div class="comunicados-contenedor">
    <div class="buscador-contenedor">
        <!-- buscador -->
        <input class="buscador form-control" type="text" id="search" placeholder="Buscar comunicados">
    </div>
    <!-- cards -->
    <div class="cards" id="comunicados">
        <!-- mostrar las cards -->
        <?php foreach ($comunicados as $comunicado): ?>
            <div class="card">
                <div class="img-contenedor">
                    <img src="<?php echo $comunicado['imagen']; ?>" class="card-img-top" alt="Imagen del Comunicado">
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $comunicado['titulo']; ?></h5>
                    <p class="card-text"><?php echo substr($comunicado['descripcion'], 0, 30); ?>...</p>
                    <p class="fecha">Fecha: <?php echo $comunicado['fecha']; ?></p>
                    <a class="btn btn-primary" href="detallecomunicado.php?id=<?php echo $comunicado['id']; ?>">Ver más</a>
                    <?php if (isset($allowed_permissions['editar'])): ?>
                        <a class="btn btn-secondary" href="editarcomunicado.php?id=<?php echo $comunicado['id']; ?>">Editar</a>
                    <?php endif; ?>
                    <?php if (isset($allowed_permissions['eliminar'])): ?>
                        <a class="btn btn-danger" href="eliminarcomunicado.php?id=<?php echo $comunicado['id']; ?>">Eliminar</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (isset($allowed_permissions['crear'])): ?>
        <div class="form-contenedor">
            <form action="item3.php" method="POST" enctype="multipart/form-data">
                <div class="titulo">
                    <p>Crear un Comunicado</p>
                </div>
                <div>
                    <label for="titulo">Título:</label>
                    <input maxlength="21" class="form-control" type="text" id="titulo" name="titulo" required>
                </div>
                <div>
                    <label for="descripcion">Descripción:</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" required cols="30" rows="1"></textarea>
                </div>
                <div>
                    <label for="autor">Autor:</label>
                    <input class="form-control" type="text" id="autor" name="autor" required>
                </div>
                <div class="img">
                    <label for="imagen">Imagen:</label>
                    <input class="form-control" type="file" id="imagen" name="imagen">
                </div>
                <div class="btn">
                    <button class="btn btn-success" type="submit"><i class="fa-solid fa-square-plus"></i> Crear</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Paginacion -->
    <div class="paginacion">
        <?php if ($page > 1): ?>
            <a class="btn btn-secondary" href="?page=<?php echo $page - 1; ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a class="btn btn-primary" href="?page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $pages): ?>
            <a class="btn btn-secondary" href="?page=<?php echo $page + 1; ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>
</div>

<script>
    document.getElementById('search').addEventListener('input', function () {
        let search = this.value;
        fetch('busquedacomunicado.php?search=' + search)
            .then(response => response.text())
            .then(data => {
                document.getElementById('comunicados').innerHTML = data;
            });
    });
</script>

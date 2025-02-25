<?php
require 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id']; // Suponiendo que ya tenemos el rol_id en la sesión

// Obtener los permisos del rol del usuario
$select_permissions = $pdo->prepare("SELECT modulo, permiso, valor FROM `roles_permisos` JOIN `permisos` ON roles_permisos.id_permiso = permisos.id WHERE id_rol = ? AND modulo = 'servicios'");
$select_permissions->execute([$role_id]);
$permissions = $select_permissions->fetchAll(PDO::FETCH_ASSOC);

$allowed_permissions = [];
foreach ($permissions as $permission) {
    if ($permission['valor'] == 1) {
        $allowed_permissions[$permission['permiso']] = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($allowed_permissions['crear'])) {
    $tipo_servicio = $_POST['tipo_servicio'];
    $imagen = 'uploads/default_tipo_servicio.jpg'; // Imagen por defecto

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $stmt = $pdo->prepare('INSERT INTO tipo_servicio (imagen, tipo_servicio) VALUES (?, ?)');
    $stmt->execute([$imagen, $tipo_servicio]);

    header('Location: admin_page.php');
}

$stmt = $pdo->query("SELECT * FROM tipo_servicio");
$tipos_servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="servicios-contenedor">
    <!-- <h4>Servicios</h4> -->
    <div class="servicios">

        <!-- <div class="serv-portada">
            <img src="./img/servicios/serv-7.jpeg" alt="">
        </div> -->

        <?php if (isset($allowed_permissions['crear'])): ?>
            <form action="item4.php" method="post" enctype="multipart/form-data">
                <p>Registrar Servicio</p>
                <label for="tipo_servicio">Tipo de Servicio:</label>
                <input class="form-control" type="text" name="tipo_servicio" required>
                <br>
                <label for="imagen">Imagen:</label>
                <input class="form-control" type="file" name="imagen">
                <br>
                <button class="btn btn-success" type="submit"><i class="fa-solid fa-plus"></i> Crear</button>
            </form>
        <?php endif; ?>

        <div class="serv-info">
            <div class="titulo">
                <p>Servicios</p>
            </div>
            <div class="cards-contenedor">
                <?php foreach ($tipos_servicios as $tipo_servicio): ?>
                    <div class="card-servs">
                        <a href="servicios.php?tipo_servicio_id=<?php echo $tipo_servicio['id'] ?>" class="card-servs-body">
                            <div class="img-contenedor">
                                <!-- <i class="fas fa-ambulance"></i> -->
                                <img src="<?php echo $tipo_servicio['imagen'] ?>" alt="<?php echo $tipo_servicio['tipo_servicio'] ?>">
                            </div>
                            <div class="titulo">
                                <h4><?php echo $tipo_servicio['tipo_servicio'] ?></h4>
                            </div>
                        </a>
                        <div class="opciones">
                            <?php if (isset($allowed_permissions['editar'])): ?>
                                <a class="btn btn-info" href="editar_tiposervicio.php?id=<?php echo $tipo_servicio['id'] ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            <?php endif; ?>
                            <?php if (isset($allowed_permissions['eliminar'])): ?>
                                <a class="btn btn-secondary" href="eliminar_tiposervicio.php?id=<?php echo $tipo_servicio['id'] ?>"><i class="fa-solid fa-trash"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
include('config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener todas las áreas para el select
$sql = "SELECT * FROM area";
$stmt = $pdo->query($sql);
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id']; // Suponiendo que ya tenemos el rol_id en la sesión

// Obtener los permisos del rol del usuario
$select_permissions = $pdo->prepare("SELECT modulo, permiso, valor FROM `roles_permisos` JOIN `permisos` ON roles_permisos.id_permiso = permisos.id WHERE id_rol = ? AND modulo = 'formularios'");
$select_permissions->execute([$role_id]);
$permissions = $select_permissions->fetchAll(PDO::FETCH_ASSOC);

$allowed_permissions = [];
foreach ($permissions as $permission) {
    if ($permission['valor'] == 1) {
        $allowed_permissions[$permission['permiso']] = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($allowed_permissions['crear'])) {
    // Procesar el archivo subido
    $nombre_archivo = $_FILES['archivo']['name'];
    $ruta_temporal = $_FILES['archivo']['tmp_name'];
    $ruta_destino = 'uploads/' . $nombre_archivo;

    if (move_uploaded_file($ruta_temporal, $ruta_destino)) {
        // Insertar en la base de datos
        $area_id = $_POST['area_id'];
        $sql = "INSERT INTO archivo (nombre_archivo, ruta_archivo, area_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_archivo, $ruta_destino, $area_id]);

        echo "Archivo subido correctamente!";
    } else {
        echo "Error al subir el archivo.";
    }

    header('Location: admin_page.php');
}
?>

<div class="row">
    <div class="col"></div>
    <div class="col-5">
        <?php if (isset($allowed_permissions['crear'])): ?>
            <h3>Insertar nuevo</h3>
            <form action="item6.php" class="form-insertar" method="post" enctype="multipart/form-data">
                <label for="area_id">Área:</label>
                <select class="form-control" name="area_id" required>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?= $area['id']; ?>"><?= $area['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
                    
                <label for="archivo">Archivo:</label>
                <input class="form-control" type="file" name="archivo" required>
                    
                <button class="btn btn-success" type="submit">Subir archivo</button>
            </form>
        <?php endif; ?>
    </div>
    <div class="col"></div>
</div>

<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <?php
        // Obtener los archivos de la base de datos
        $sql = "SELECT a.id, a.nombre_archivo, a.ruta_archivo, ar.nombre AS area_nombre 
                FROM archivo a
                JOIN area ar ON a.area_id = ar.id";
        $stmt = $pdo->query($sql);
        $archivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Función para determinar el ícono del archivo basado en la extensión
        function obtenerIcono($extension) {
            switch ($extension) {
                case 'pdf':
                    return 'pdf_icon.png'; // Asegúrate de tener una imagen pdf_icon.png en tu carpeta de imágenes
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    return 'image_icon.png'; // Imagen para archivos de imagen
                case 'doc':
                case 'docx':
                    return 'word_icon.png'; // Imagen para archivos de Word
                case 'xls':
                case 'xlsm':
                case 'xlsx':
                case 'xltx':
                case 'xltm':
                    return 'excel_icon.png'; // Imagen para archivos de excel
                case 'pptx':
                    return 'powerpoint_icon.png'; // Imagen para archivos de powerpoint
                case 'zip':
                    return 'zip_icon.png'; // Imagen para archivos comprimidos
                default:
                    return 'default_icon.png'; // Imagen por defecto
            }
        }
        ?>

        <table id="myTableFormularios" class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>Nombre del archivo</th>
                    <th>Área</th>
                    <th>Acción</th>
                    <th>Tipo de Archivo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos as $archivo): ?>
                    <tr>
                        <td><?= htmlspecialchars($archivo['nombre_archivo']); ?></td>
                        <td><?= htmlspecialchars($archivo['area_nombre']); ?></td>
                        <td><a class="btn btn-primary" href="<?= $archivo['ruta_archivo']; ?>" download><i class="fas fa-download"></i> Descargar</a></td>
                        <td>
                            <?php
                            // Obtener la extensión del archivo
                            $extension = pathinfo($archivo['nombre_archivo'], PATHINFO_EXTENSION);
                            $icono = obtenerIcono(strtolower($extension));
                            ?>
                            <img src="icons/<?= $icono; ?>" alt="Icono" width="32" height="32">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-2"></div>
</div>

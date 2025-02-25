<?php
include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener todos los contactos junto con su área
$sql = "SELECT c.id, c.foto, c.nombre, c.contacto, a.nombre AS area 
        FROM contactos c
        LEFT JOIN area a ON c.id_area = a.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Determinar el rol y el ID del usuario actual
$role_id = $_SESSION['role_id']; // Suponiendo que ya tenemos el rol_id en la sesión

// Obtener los permisos del rol del usuario
$select_permissions = $pdo->prepare("SELECT modulo, permiso, valor FROM `roles_permisos` JOIN `permisos` ON roles_permisos.id_permiso = permisos.id WHERE id_rol = ? AND modulo = 'contactos'");
$select_permissions->execute([$role_id]);
$permissions = $select_permissions->fetchAll(PDO::FETCH_ASSOC);

$allowed_permissions = [];
foreach ($permissions as $permission) {
    if ($permission['valor'] == 1) {
        $allowed_permissions[$permission['permiso']] = true;
    }
}
?>

<div class="row">
    <div class="col"></div>
    <div class="col-10">
        <!-- Botón para agregar contacto (permiso de crear) -->
        <?php if (isset($allowed_permissions['crear'])): ?>
            <a class="btn btn-success" href="crearcontacto.php">Agregar Contacto</a>
        <?php endif; ?>
        <br>
        <h1>Lista de Contactos</h1>
        <!-- Lista de contactos (permiso de ver) -->
        <?php if (isset($allowed_permissions['ver'])): ?>
            <table id="myTable" class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nombre</th>
                        <th>Interno</th>
                        <th>Área</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                        <tr>
                            <td><?= $contact['id']; ?></td>
                            <td><img src="ver_imagencontacto.php?id=<?= $contact['id']; ?>" alt="Foto" width="50"></td>
                            <td><?= $contact['nombre']; ?></td>
                            <td><img src="img/icono-whatsapp.png" alt="Foto" width="35"><?= $contact['contacto']; ?></td>
                            <td><?= $contact['area']; ?></td>
                            <td>
                                <!-- Botones de editar y eliminar (permiso de editar y eliminar) -->
                                <?php if (isset($allowed_permissions['editar'])): ?>
                                    <a class="btn btn-secondary" href="editarcontacto.php?id=<?= $contact['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                <?php endif; ?>
                                <?php if (isset($allowed_permissions['eliminar'])): ?>
                                    <a class="btn btn-danger" href="eliminarcontacto.php?id=<?= $contact['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div class="col"></div>
</div>
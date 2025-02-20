<?php
// include 'header.php';

// admin_roles muestra el crud de roles registrados

// Solo permitir acceso si el usuario es administrador
if ($fetch_profile['user_type'] !== 'admin') {
    header('location:admin_page.php');
    exit;
}

if (isset($_POST['create_role'])) {
    $user_type = $_POST['user_type'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $create_role = $pdo->prepare("INSERT INTO `roles` (user_type, descripcion, estado) VALUES (?, ?, ?)");
    $create_role->execute([$user_type, $descripcion, $estado]);
}

if (isset($_POST['update_role'])) {
    $update_id = $_POST['update_id'];
    $user_type = $_POST['user_type'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $update_role = $pdo->prepare("UPDATE `roles` SET user_type = ?, descripcion = ?, estado = ? WHERE id = ?");
    $update_role->execute([$user_type, $descripcion, $estado, $update_id]);
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_role = $pdo->prepare("DELETE FROM `roles` WHERE id = ?");
    $delete_role->execute([$delete_id]);
    header('location:admin_roles.php');
}

$select_roles = $pdo->prepare("SELECT * FROM `roles`");
$select_roles->execute();

if ($select_roles === false) {
    echo "Error en la consulta de selección de roles";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Administrar Roles</title>
   <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Administrar Roles</h1>
        <table id="myTable2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rol</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $select_roles->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['user_type']; ?></td>
                    <td><?= $row['descripcion']; ?></td>
                    <td><?= $row['estado']; ?></td>
                    <td>
                        <a title="Ver e ingresar permisos" href="manage_permissions.php?role_id=<?= $row['id']; ?>" class="btn btn-success"><i class="fa-solid fa-key"></i></a>
                        <button title="Editar rol" class="btn btn-secondary" onclick="editarRol(<?= $row['id']; ?>, '<?= $row['user_type']; ?>', '<?= $row['descripcion']; ?>', '<?= $row['estado']; ?>')"><i class="fa-solid fa-pen-to-square"></i></button>
                        <a title="Eliminar rol" href="admin_roles.php?delete=<?= $row['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este rol?');" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h2>Crear Nuevo Rol</h2>
        <form method="post">
            <input type="text" name="user_type" placeholder="Rol" required>
            <input type="text" name="descripcion" placeholder="Descripción" required>
            <input type="text" name="estado" placeholder="Estado" required>
            <button type="submit" name="create_role" class="btn">Crear Rol</button>
        </form>

        <!-- Ventana Modal para Editar Rol -->
        <div id="modalEditarRol" class="modal">
            <div class="modal-content">
                <span class="close" onclick="cerrarModalRol()">&times;</span>
                <h2>Editar Rol</h2>
                <form id="editRoleForm">
                    <input type="hidden" name="update_id" id="edit_role_id">
                    <label for="edit_user_type">Rol:</label>
                    <input type="text" name="user_type" id="edit_user_type" required>
                    <label for="edit_descripcion">Descripción:</label>
                    <input type="text" name="descripcion" id="edit_descripcion" required>
                    <label for="edit_estado">Estado:</label>
                    <input type="text" name="estado" id="edit_estado" required>
                    <button type="submit" class="btn">Actualizar Rol</button>
                    <button type="button" class="btn" onclick="cerrarModalRol()">Cancelar</button>
                </form>
            </div>
        </div>

        <script>
        $(document).ready(function() {
            // Manejar la edición del rol
            $('#editRoleForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: 'update_role.php',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        alert(data);
                        cerrarModalRol();
                        location.reload();
                    }
                });
            });

            // Funciones para abrir y cerrar el modal de edición de rol
            window.editarRol = function(id, user_type, descripcion, estado) {
                $('#edit_role_id').val(id);
                $('#edit_user_type').val(user_type);
                $('#edit_descripcion').val(descripcion);
                $('#edit_estado').val(estado);
                $('#modalEditarRol').show();
            };

            window.cerrarModalRol = function() {
                $('#modalEditarRol').hide();
            };
        });
        </script>
    </div>
</body>
</html>

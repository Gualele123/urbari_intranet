<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['rol']) || !isset($_SESSION['user_id'])) {
    die("Error: No se ha iniciado sesión correctamente.");
}

$rol = $_SESSION['rol'];
$id = $_SESSION['user_id'];

// Solo permitir acceso si el usuario es administrador
if ($rol !== 'admin') {
    header('location:admin_page.php');
    exit;
}

// Consulta para obtener los usuarios y sus roles
$select_users = $pdo->prepare("SELECT users.id, users.name, users.email, users.image, roles.rol FROM users JOIN roles ON users.rol_id = roles.id");
$select_users->execute();
?>

    <div class="container">
        <h1>Administrar Usuarios</h1>
        <table id="myTableUsers" class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php while ($row = $select_users->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= $row['name']; ?></td>
                    <td><?= $row['email']; ?></td>
                    <td><?= $row['rol']; ?></td>
                    <td><img class='img-thumbnail' src='uploaded_img/<?= $row['image']; ?>' alt='' width='50'></td>
                    <td>
                        <button class='btn btn-primary' onclick="verImagen(<?= $row['id']; ?>, 'uploaded_img/<?= $row['image']; ?>', '<?= $row['name']; ?>', '<?= $row['email']; ?>', '<?= $row['rol']; ?>')"><i class='fa-solid fa-eye'></i></button>
                        <button class='btn btn-secondary' onclick="editarUsuario(<?= $row['id']; ?>, '<?= $row['name']; ?>', '<?= $row['email']; ?>', '<?= $row['rol']; ?>', 'uploaded_img/<?= $row['image']; ?>')"><i class='fa-solid fa-pen-to-square'></i></button>
                        <button class='btn btn-danger' onclick="eliminarUsuario(<?= $row['id']; ?>)"><i class='fa-solid fa-trash'></i></button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para Ver Imagen -->
    <div id="modalVerImagen" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalVerImagen()">&times;</span>
            <div class="modal-body">
                <div class="left-column">
                    <img id="imagenUsuario" class='img-thumbnail' src='' alt=''>
                </div>
                <div class="right-column">
                    <h2>Detalles del Usuario</h2>
                    <p><strong>ID:</strong> <span id="detalleID"></span></p>
                    <p><strong>Nombre:</strong> <span id="detalleNombre"></span></p>
                    <p><strong>Email:</strong> <span id="detalleEmail"></span></p>
                    <p><strong>Rol:</strong> <span id="detalleRol"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Usuario -->
    <div id="modalEditarUsuario" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2>Editar Usuario</h2>
            <form id="editUserForm" enctype="multipart/form-data">
                <input class="form-control" type="hidden" name="update_id" id="edit_id">
                <label for="name">Nombre:</label>
                <input class="form-control" type="text" name="name" id="edit_name" required>
                <label for="email">Email:</label>
                <input class="form-control" type="email" name="email" id="edit_email" required>
                <label for="rol">Rol:</label>
                <select class="form-control" name="rol" id="edit_user_type" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="colaborador">Colaborador</option>
                    <option value="recursos humanos">Recursos Humanos</option>
                </select>
                <label for="image">Imagen:</label>
                <input class="form-control" type="file" name="image" id="edit_image" accept="image/*">
                <button type="submit" class="btn btn-success btn-editusuario">Actualizar Usuario</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Función para abrir el modal de ver imagen
        window.verImagen = function(id, image, name, email, rol) {
            $('#detalleID').text(id);
            $('#detalleNombre').text(name);
            $('#detalleEmail').text(email);
            $('#detalleRol').text(rol);
            $('#imagenUsuario').attr('src', image);
            $('#modalVerImagen').show();
        }

        // Función para cerrar el modal de ver imagen
        window.cerrarModalVerImagen = function() {
            $('#modalVerImagen').hide();
        }

        // Función para abrir el modal de edición
        window.editarUsuario = function(id, name, email, rol, image) {
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_user_type').val(rol);
            $('#modalEditarUsuario').show();
        }

        // Función para cerrar el modal de edición
        window.cerrarModal = function() {
            $('#modalEditarUsuario').hide();
        }

        // Manejar la actualización del usuario mediante AJAX
        $('#editUserForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'update_user.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert(data);
                    cerrarModal();
                    location.reload();
                }
            });
        });

        // Manejar la eliminación del usuario mediante AJAX
        window.eliminarUsuario = function(id) {
            if (confirm('¿Estás seguro de eliminar este usuario?')) {
                $.ajax({
                    url: 'delete_user.php',
                    type: 'GET',
                    data: { delete_id: id },
                    success: function(data) {
                        alert(data);
                        location.reload();
                    }
                });
            }
        }
    });
    </script>

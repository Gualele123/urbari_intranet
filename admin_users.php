<?php
// include 'header.php';

// admin_users muestra el crud de usuarios registrados

// Solo permitir acceso si el usuario es administrador
if ($fetch_profile['user_type'] !== 'admin') {
    header('location:admin_page.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Administrar Usuarios</title>
   <link rel="stylesheet" href="styles.css">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Administrar Usuarios</h1>
        <table id="myTable">
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
                <!-- Los datos de los usuarios se cargarán aquí mediante AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Modal para Editar Usuario -->
    <div id="modalEditarUsuario" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2>Editar Usuario</h2>
            <form id="editUserForm" enctype="multipart/form-data">
                <input type="hidden" name="update_id" id="edit_id">
                <label for="name">Nombre:</label>
                <input type="text" name="name" id="edit_name" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="edit_email" required>
                <label for="user_type">Rol:</label>
                <select name="user_type" id="edit_user_type" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="colaborador">Colaborador</option>
                    <option value="recursos humanos">Recursos Humanos</option>
                </select>
                <label for="image">Imagen:</label>
                <input type="file" name="image" id="edit_image" accept="image/*">
                <button type="submit" class="btn">Actualizar Usuario</button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        cargarUsuarios();

        // Función para cargar usuarios cada 5 segundos
        setInterval(cargarUsuarios, 5000);

        // Función para cargar usuarios
        function cargarUsuarios() {
            $.ajax({
                url: 'fetch_users.php',
                type: 'GET',
                success: function(data) {
                    $('#userTableBody').html(data);
                }
            });
        }

        // Función para abrir el modal de edición
        window.editarUsuario = function(id, name, email, user_type, image) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_user_type').value = user_type;
            document.getElementById('modalEditarUsuario').style.display = 'block';
        }

        // Función para cerrar el modal de edición
        window.cerrarModal = function() {
            document.getElementById('modalEditarUsuario').style.display = 'none';
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
                    cargarUsuarios();
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
                        cargarUsuarios();
                    }
                });
            }
        }
    });
    </script>
</body>
</html>

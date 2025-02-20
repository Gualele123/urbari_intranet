


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

    // Manejar la asignación de permisos
    $('#permisosForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'save_permissions.php',
            type: 'POST',
            data: formData,
            success: function(data) {
                alert(data);
                cerrarModalPermisos();
            }
        });
    });

    // Funciones para abrir y cerrar el modal de permisos
    window.mostrarPermisos = function(role_id) {
        $('#role_id').val(role_id);

        // Cargar permisos actuales
        $.ajax({
            url: 'fetch_permissions.php',
            type: 'GET',
            data: { role_id: role_id },
            success: function(data) {
                $('#permisosBody').html(data);
                $('#modalPermisos').show();
            }
        });
    };

    // Función para cerrar el modal de permisos
    window.cerrarModalPermisos = function() {
        $('#modalPermisos').hide();
    };

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

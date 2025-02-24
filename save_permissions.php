<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];
    $modulos = [
        'dashboard',
        'usuarios',
        'roles',
        'cumpleaneros',
        'comunicados',
        'servicios',
        'contactos',
        'formularios'
    ];

    $permisos = [
        'ver',
        'crear',
        'editar',
        'eliminar'
    ];

    // Eliminar permisos existentes para el rol
    $delete_permissions = $pdo->prepare("DELETE FROM `roles_permisos` WHERE id_rol = ?");
    $delete_permissions->execute([$role_id]);

    // Insertar o actualizar permisos
    foreach ($modulos as $modulo) {
        foreach ($permisos as $permiso) {
            $valor = isset($_POST["{$modulo}_{$permiso}"]) ? 1 : 0;

            // Obtener el id_permiso correspondiente al permiso
            $select_permiso = $pdo->prepare("SELECT id FROM `permisos` WHERE permiso = ?");
            $select_permiso->execute([$permiso]);
            $result = $select_permiso->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $id_permiso = $result['id'];

                // Insertar o actualizar el permiso
                $insert_permiso = $pdo->prepare("INSERT INTO `roles_permisos` (id_rol, id_permiso, modulo, valor) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)");
                $insert_permiso->execute([$role_id, $id_permiso, $modulo, $valor]);
            } else {
                echo "Error: El permiso '$permiso' no existe en la tabla 'permisos'.";
                exit();
            }
        }
    }

    // Redirigir a la página de tabla de roles
    header('Location: admin_page.php'); // Cambia a la URL de la página de tabla de roles
    exit();
} else {
    echo "Método de solicitud no permitido";
}
?>

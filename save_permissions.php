<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_id = $_POST['role_id'];
    $modulos = [
        'dashboard',
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

    // Eliminar permisos existentes
    $delete_permissions = $pdo->prepare("DELETE FROM `roles_permisos` WHERE role_id = ?");
    $delete_permissions->execute([$role_id]);

    // Insertar nuevos permisos
    foreach ($modulos as $modulo) {
        foreach ($permisos as $permiso) {
            $valor = isset($_POST["{$modulo}_{$permiso}"]) ? 1 : 0;
            $insert_permiso = $pdo->prepare("INSERT INTO `roles_permisos` (role_id, modulo, permiso, valor) VALUES (?, ?, ?, ?)");
            $insert_permiso->execute([$role_id, $modulo, $permiso, $valor]);
        }
    }

    // Redirigir de nuevo a la página de roles
    header('Location: admin_roles.php');
    exit();
} else {
    echo "Método de solicitud no permitido";
}
?>

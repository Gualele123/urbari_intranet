<?php
include 'config.php';

$role_id = $_GET['role_id'];
$select_permissions = $pdo->prepare("SELECT * FROM `roles_permisos` WHERE role_id = ?");
$select_permissions->execute([$role_id]);

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

foreach ($modulos as $modulo) {
    echo "<tr>
            <td>" . ucfirst($modulo) . "</td>";
    foreach ($permisos as $permiso) {
        $select_permiso = $pdo->prepare("SELECT * FROM `roles_permisos` WHERE role_id = ? AND modulo = ? AND permiso = ?");
        $select_permiso->execute([$role_id, $modulo, $permiso]);
        $row = $select_permiso->fetch(PDO::FETCH_ASSOC);
        $checked = $row && $row['valor'] ? 'checked' : '';
        echo "<td><input type='checkbox' name='{$modulo}_{$permiso}' $checked></td>";
    }
    echo "</tr>";
}
?>

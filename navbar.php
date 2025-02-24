<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Asegurarse de que el usuario haya iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener el rol del usuario
$select_user = $pdo->prepare("SELECT rol_id FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user = $select_user->fetch(PDO::FETCH_ASSOC);

$role_id = $user['rol_id'];

// Comprobar si el usuario es administrador
$is_admin = false;
$select_role = $pdo->prepare("SELECT rol FROM `roles` WHERE id = ?");
$select_role->execute([$role_id]);
$role = $select_role->fetch(PDO::FETCH_ASSOC);

if ($role && $role['rol'] === 'admin') {
    $is_admin = true;
}

// Obtener los permisos del rol del usuario
$select_permissions = $pdo->prepare("SELECT modulo, permiso, valor FROM `roles_permisos` JOIN `permisos` ON roles_permisos.id_permiso = permisos.id WHERE id_rol = ?");
$select_permissions->execute([$role_id]);
$permissions = $select_permissions->fetchAll(PDO::FETCH_ASSOC);

// Construir una matriz de permisos
$allowed_modules = [];
foreach ($permissions as $permission) {
    if ($permission['valor'] == 1) {
        $allowed_modules[$permission['modulo']][$permission['permiso']] = true;
    }
}

if ($is_admin) {
    // Dar acceso a todos los módulos y permisos por defecto al administrador
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

    foreach ($modulos as $modulo) {
        foreach ($permisos as $permiso) {
            $allowed_modules[$modulo][$permiso] = true;
        }
    }
}

?>
<ul class="nav flex-column navbar-dark bg-primary">
        <?php if (isset($allowed_modules['dashboard']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn active" href="#"><i class="fa-solid fa-house"></i> Dashboard</a>
            </li>
        <?php endif; ?>

        <?php if (isset($allowed_modules['usuarios']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn" href="#"><i class="fa-solid fa-users"></i> Usuarios</a>
            </li>
        <?php endif; ?>

        <?php if (isset($allowed_modules['roles']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn" href="#"><i class="fa-solid fa-key"></i> Roles</a>
            </li>
        <?php endif; ?>

        <?php if (isset($allowed_modules['cumpleaneros']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn" href="#"><i class="fa-solid fa-cake-candles"></i> Cumpleañeros</a>
            </li>
        <?php endif; ?>

        <?php if (isset($allowed_modules['comunicados']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn" href="#"><i class="fas fa-bullhorn"></i> Comunicados</a>
            </li>
        <?php endif; ?>

        <?php if (isset($allowed_modules['servicios']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn" href="#"><i class="fas fa-stethoscope"></i> Servicios</a>
            </li>
        <?php endif; ?>

        <?php if (isset($allowed_modules['contactos']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn" href="#"><i class="fas fa-address-book"></i> Contactos</a>
            </li>
        <?php endif; ?>

        <?php if (isset($allowed_modules['formularios']['ver'])): ?>
            <li class="nav-item">
                <a class="nav-link tab_btn" href="#"><i class="fas fa-folder-open"></i> Formularios</a>
            </li>
        <?php endif; ?>
    </ul>
</div>

<!-- main -->
<main class="seccion main-contenedor">

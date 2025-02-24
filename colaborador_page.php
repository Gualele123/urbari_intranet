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

<?php
include 'header.php';
include 'navbar.php';
?>

<div class="container">
    <h1>Bienvenido a la Página de Colaborador</h1>

    <!-- DASHBOARD -->
    <?php if (isset($allowed_modules['dashboard']['ver'])): ?>
        <div class="item active">
            <?php include 'item1.php'; ?>
        </div>
    <?php endif; ?>

    <!-- ADMIN USERS -->
    <?php if (isset($allowed_modules['usuarios']['ver'])): ?>
        <div class="item">
            <?php include 'admin_users.php'; ?>
        </div>
    <?php endif; ?>

    <!-- ADMIN ROLES -->
    <?php if (isset($allowed_modules['roles']['ver'])): ?>
        <div class="item">
            <?php include 'admin_roles.php'; ?>
        </div>
    <?php endif; ?>

    <!-- CUMPLEAÑEROS -->
    <?php if (isset($allowed_modules['cumpleaneros']['ver'])): ?>
        <div class="item">
            <?php include 'item2.php'; ?>
        </div>
    <?php endif; ?>

    <!-- COMUNICADOS -->
    <?php if (isset($allowed_modules['comunicados']['ver'])): ?>
        <div class="item">
            <?php include 'item3.php'; ?>
        </div>
    <?php endif; ?>

    <!-- SERVICIOS -->
    <?php if (isset($allowed_modules['servicios']['ver'])): ?>
        <div class="item">
            <?php include 'item4.php'; ?>
        </div>
    <?php endif; ?>

    <!-- CONTACTOS -->
    <?php if (isset($allowed_modules['contactos']['ver'])): ?>
        <div class="item">
            <?php include 'item5.php'; ?>
        </div>
    <?php endif; ?>

    <!-- FORMULARIOS -->
    <?php if (isset($allowed_modules['formularios']['ver'])): ?>
        <div class="item">
            <?php include 'item6.php'; ?>
        </div>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>

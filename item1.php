<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si las variables de sesión están definidas
if (!isset($_SESSION['rol']) || !isset($_SESSION['user_id'])) {
    die("Error: No se ha iniciado sesión correctamente.");
}

// Determinar el tipo de rol e ID del usuario actual
$role_type = $_SESSION['rol'];
$id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id']; // Suponiendo que ya tenemos el rol_id en la sesión

// Consulta para obtener el perfil del usuario
$select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

// Mensaje personalizado basado en el tipo de rol
$mensaje_rol = '';

switch ($role_type) {
    case 'admin':
        $mensaje_rol = 'Intranet Clinica Urbari para Administrador';
        break;
    case 'user':
        $mensaje_rol = 'Intranet Clinica Urbari para Usuario Normal';
        break;
    case 'colaborador':
        $mensaje_rol = 'Intranet Clinica Urbari para Colaborador';
        break;
    case 'recursos humanos':
        $mensaje_rol = 'Intranet Clinica Urbari para Usuario Recursos Humanos';
        break;
}

// Obtener los permisos del rol del usuario
$select_permissions = $pdo->prepare("SELECT modulo, permiso, valor FROM `roles_permisos` JOIN `permisos` ON roles_permisos.id_permiso = permisos.id WHERE id_rol = ? AND modulo = 'dashboard'");
$select_permissions->execute([$role_id]);
$permissions = $select_permissions->fetchAll(PDO::FETCH_ASSOC);

$allowed_permissions = [];
foreach ($permissions as $permission) {
    if ($permission['valor'] == 1) {
        $allowed_permissions[$permission['permiso']] = true;
    }
}
?>

<div class='dashboard-contenido'>
    <div class="contenido">
        <h2 style="color: rgb(0, 255, 242); font-size: 2.4rem;"><?= $mensaje_rol; ?></h2>
        <p style="font-size: 1.4rem;">Tu Portal de Informacion y Recursos</p>
        <a style="color: rgb(0, 255, 242); font-size: 3.4rem; text-align: center;" class="nav-link active">Bienvenido <?= $fetch_profile['name']; ?></a>
    </div>
</div>

<!-- Lista de registros -->
<?php if (isset($allowed_permissions['ver'])): ?>
    <table>
        <!-- Contenido de la tabla -->
         <h4>Hola aqui va la tabla</h4>
    </table>
<?php endif; ?>

<!-- Formulario para crear nuevo registro -->
<?php if (isset($allowed_permissions['crear'])): ?>
    <form id="crear_dashboard">
        <!-- Campos del formulario -->
        <button type="submit">Crear</button>
    </form>
<?php endif; ?>

<!-- Botones de editar y eliminar en la tabla -->
<table>
    <?php if (isset($allowed_permissions['ver'])): ?>
        <!-- Contenido de la tabla -->
        <?php if (isset($allowed_permissions['editar'])): ?>
            <button>Editar</button>
        <?php endif; ?>
        <?php if (isset($allowed_permissions['eliminar'])): ?>
            <button>Eliminar</button>
        <?php endif; ?>
    <?php endif; ?>
</table>

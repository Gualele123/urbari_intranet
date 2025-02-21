<?php
include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si las variables de sesión están definidas
if (!isset($_SESSION['role_type']) || !isset($_SESSION['user_id'])) {
    die("Error: No se ha iniciado sesión correctamente.");
}

// Determinar el tipo de rol e ID del usuario actual
$role_type = $_SESSION['role_type'];
$id = $_SESSION['user_id'];

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
?>

<div class='dashboard-contenido'>
    <div class="contenido">
        <h2 style="color: rgb(0, 255, 242); font-size: 2.4rem;"><?= $mensaje_rol; ?></h2>
        <p style="font-size: 1.4rem;">Tu Portal de Informacion y Recursos</p>
        <a style="color: rgb(0, 255, 242); font-size: 3.4rem; text-align: center;" class="nav-link active">Bienvenido <?= $fetch_profile['name']; ?></a>
    </div>
</div>

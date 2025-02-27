<?php
include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id = '';
$role_type = '';

// Determinar el ID y el tipo de rol del usuario actual
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$id]);
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

    $role_id = $fetch_profile['rol_id'];
    $select_role = $pdo->prepare("SELECT * FROM `roles` WHERE id = ?");
    $select_role->execute([$role_id]);
    $role = $select_role->fetch(PDO::FETCH_ASSOC);
    $role_type = $role['rol'];
} else {
    // Redirigir a la página de inicio si el usuario no ha iniciado sesión
    header('location:index.php');
    exit;
}

// Verificar si el rol del usuario coincide con el rol almacenado en la sesión
if ($fetch_profile['rol_id'] != $role_id) {
    header('location:index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de <?= ucfirst($role_type); ?></title>
    <link rel="stylesheet" href="styles.css">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <link rel="icon" href="img/logo.png">
    <link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body> 
    <!-- <h1 class="title"> <span><?= ucfirst($role_type); ?></span> Página de Perfil</h1> -->
    <section class="contenedor">
        <section class="contenedor-intranet">
            <?php 
            $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ?"); 
            $select_profile->execute([$id]); 
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC); 
            ?>
            <!-- header -->
            <div class="seccion header-contenedor">
                <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                    <div class="container-fluid"> 
                        <a class="navbar-brand" href="#"> 
                            <img src="./img/logo.png" alt="" width="30" height="24" class="d-inline-block align-text-top"> 
                            Clinica Urbarí 
                        </a>
                        <ul class="nav justify-content-end">
                            <li class="nav-item"> 
                                <a class="nav-link titulo">
                                    <?= $fetch_profile['name']; ?>
                                </a> 
                            </li>
                            <li class="nav-item">
                                <div class="img-contenedor"> 
                                    <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
                                    <div class="circulo-live"><i class="fa-solid fa-circle punto"></i></div>
                                </div>
                            </li>
                            <li class="nav-item dropdown"> 
                                <a class="nav-link dropdown-toggle danger" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
                                    <i class="fa-solid fa-right-from-bracket"></i> 
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                    <li><a class="dropdown-item" href="logout.php">Salir</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div> 
            <!-- navbar -->
            <div class="seccion navbar-contenedor navbar-dark bg-primary">

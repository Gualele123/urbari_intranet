<?php
include 'config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determinar el tipo de rol e ID del usuario actual
$role_type = '';
$id = '';

if (isset($_SESSION['admin_id'])) {
    $role_type = 'admin';
    $id = $_SESSION['admin_id'];

} elseif (isset($_SESSION['user_id'])) {
    $role_type = 'user';
    $id = $_SESSION['user_id'];

} elseif (isset($_SESSION['colaborador_id'])) {
    $role_type = 'colaborador';
    $id = $_SESSION['colaborador_id'];
    
} elseif (isset($_SESSION['rh_id'])) {
    $role_type = 'recursos humanos';
    $id = $_SESSION['rh_id'];
}

// Redirigir a la página de inicio si el usuario no ha iniciado sesión
if (empty($id)) {
    header('location:index.php');
    exit;
}

// Consulta para obtener el perfil del usuario
$select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Página de <?= ucfirst($role_type); ?></title>
   <link rel="stylesheet" href="styles.css">
   <link rel="icon" href="img/logo.png">
   <!-- libreria css externa swiper para carousel-->
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"> -->
   <link rel="stylesheet" href="//cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css">
   <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.min.css"> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
   <!-- font awesome -->
   <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> -->
    <!-- mi css -->
     <!-- <link rel="stylesheet" href="css/style.css"> -->
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
                        <a class="nav-link titulo"><?= $fetch_profile['name']; ?></a>
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

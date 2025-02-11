<?php
include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:index.php');
}


?>


<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pagina Admin</title>
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
   <!-- <h1 class="title"> <span>admin</span> pagina de perfil</h1> -->


      <section class="contenedor">
        <section class="contenedor-intranet">
        <?php
         $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ? ");
         $select_profile->execute([$admin_id]);
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
                          <!-- <a class="nav-link active" aria-current="page" href="#">Admin</a> -->
                          <a class="nav-link titulo"><?= $fetch_profile['name']; ?></a>
                        </li>
                        <li class="nav-item">
                          <div class="img-contenedor">
                            <!-- <img src="./img/usuario.png" alt=""> -->
                            <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
                            <div class="circulo-live"><i class="fa-solid fa-circle punto"></i></div>
                          </div>
                        </li>

                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle danger" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-right-from-bracket"></i>
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                            <!-- <li><a class="dropdown-item" href="index.php">Salir</a></li> -->
                            <li><a class="dropdown-item" href="logout.php">Salir</a></li>
                          </ul>
                        </li>


                    </ul>
                  </div>
                </nav>
            </div>

            <!-- navbar -->
             <div class="seccion navbar-contenedor navbar-dark bg-primary">
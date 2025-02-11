<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
    <section class="contenedor">
        <section class="contenedor-intranet">
            
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
                          <a class="nav-link active" aria-current="page" href="#">Admin</a>
                        </li>
                        <li class="nav-item">
                          <div class="img-contenedor">
                            <img src="./img/usuario.png" alt="">
                          </div>
                        </li>

                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown- danger" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-right-from-bracket"></i>
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                            <li><a class="dropdown-item" href="index.php">Salir</a></li>
                          </ul>
                        </li>


                    </ul>
                  </div>
                </nav>
            </div>

            <!-- navbar -->
             <div class="seccion navbar-contenedor navbar-dark bg-primary">
                <ul class="nav flex-column navbar-dark bg-primary">
                  <li class="nav-item ">
                    <a class="nav-link tab_btn active" href="#"><i class="fa-solid fa-house"></i> Dashboard</a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link tab_btn" href="#"><i class="fa-solid fa-cake-candles"></i> Cumpleañeros</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link tab_btn" href="#"><i class="fas fa-bullhorn"></i> Comunicados</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link tab_btn" href="#"><i class="fas fa-stethoscope"></i> Servicios</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link tab_btn" href="#"><i class="fas fa-address-book"></i> Contactos</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link tab_btn" href="#"><i class="fas fa-folder-open"></i> Formularios</a>
                  </li>
                </ul>
             </div>

            <!-- main -->
             <main class="seccion main-contenedor">
                <div class="main">
                    <?php  include 'item1.php'; ?>
                </div>
                <div class="main">
                    <?php  include 'item2.php'; ?>
                </div>
                <div class="main">
                    <?php  include 'item3.php'; ?>
                </div>
                <div class="main">
                    <?php  include 'item4.php'; ?>
                </div>
                <div class="main active">
                    <?php  include 'item5.php'; ?>
                </div>
                <div class="main">
                    <?php  include 'item6.php'; ?>
                </div>
             </main>
        </section>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/26de86382c.js" crossorigin="anonymous"></script>
    <script src="./main.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
  <title>CRUD Archivos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
</head>
<body>
    <!--LOGIN-->
  <form class="login" action="" method="post">
    <div class="row">
      <div class="col-4">
      </div>
      <div class="col-4 card-login">
        <!-- <label for="exampleInputEmail1" class="form-label">Usuario</label> -->
        <div class="contenedor-image">
          <img src="./img/logo.png" alt="">
        </div>
        <div class="titulo-login"><h1>Bienvenido a Intranet Urbari</h1></div>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="usuario">
        <div class="mb-3">
            <!-- <label for="exampleInputPassword1" class="form-label">Contraseña</label> -->
          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="contraseña">
        </div>
        <a href="./dashboard.php" type="submit" class="btn btn-primary btnIngresar">
          Iniciar Sesión
        </a>
      </div>
      <div class="col-4"></div>
    </div>
  </form>
    


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/26de86382c.js" crossorigin="anonymous"></script>
    <script src="./main.js"></script>


</body>
</html>
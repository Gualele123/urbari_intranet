<?php
include 'config.php';

if (isset($_POST['submit'])) {
   //capturando datos desde el formulario
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   //manejo de la imagen
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_img/'.$image;

   //consulta
   $select = $pdo->prepare("SELECT * FROM `users` WHERE name = ?");
   $select->execute([$name]);

   if($select->rowCount() > 0) {
      $message[] = 'el usuario ya existe!';
   }else {
      if ($pass != $cpass) {
         $message[] = 'confirmar contraseña no coincide!';
      }elseif ($image_size > 2000000) {
         $message[] = 'el tamaño de la imagen es muy grande!';
      }
      else {
         $insert = $pdo->prepare("INSERT INTO `users`(name, email, password, image) VALUES(?,?,?,?)");
         $insert->execute([$name, $email, $cpass, $image]);
         if ($insert) {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'registrado correctamente!';
            header('location:index.php');
         }
      }
   }



}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrar</title>
   <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- mi css -->
     <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <?php
      if(isset($message)) {
         foreach($message as $message) {
            echo '
            <div class="message">
               <span>'.$message.'</span>
               <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
         }
      }
   ?>

   <section class="form-container">
      <form action="" method="post" enctype="multipart/form-data">
         <div class="logo-contenedor-register">
            <img src="./img/logo.png" alt="">
         </div>
         <h3>Registrar ahora</h3>
         <input type="text" required placeholder="ingresa usuario" class="box" name="name">
         <input type="email" required placeholder="ingrese email" class="box" name="email">
         <input type="password" required placeholder="ingrese contraseña" class="box" name="pass">
         <input type="password" required placeholder="confirme su contraseña" class="box" name="cpass">
         <input type="file" required name="image" class="box" accept="image/jpg, image/png, image/jpeg">
         <p>Ya tienes una cuenta? <a href="index.php">Iniciar Sesion</a></p>
         <input type="submit" value="Registrar" class="btn" name="submit">
      </form>

   </section>
</body>
</html>
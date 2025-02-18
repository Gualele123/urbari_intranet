<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrar</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
   <section class="form-container">
      <form id="registerForm" enctype="multipart/form-data">
         <div class="logo-contenedor-register">
            <img src="./img/logo.png" alt="">
         </div>
         <h3>Registrar ahora</h3>
         <input type="text" required placeholder="ingresa usuario" class="box" name="name">
         <input type="email" required placeholder="ingrese email" class="box" name="email">
         <input type="password" required placeholder="ingrese contraseña" class="box" name="pass">
         <input type="password" required placeholder="confirme su contraseña" class="box" name="cpass">
         <input type="file" required name="image" class="box" accept="image/*">
         <input type="submit" value="Registrar" class="btn" name="submit">
      </form>
   </section>

   <script>
   $(document).ready(function() {
      $('#registerForm').submit(function(e) {
         e.preventDefault();
         var formData = new FormData(this);

         $.ajax({
            url: 'process_register.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
               var data = JSON.parse(response);
               if (data.success) {
                  alert('Usuario registrado correctamente');
                  window.location.href = 'index.php';
               } else {
                  alert(data.error);
               }
            }
         });
      });
   });
   </script>
</body>
</html>

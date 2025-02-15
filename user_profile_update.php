<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
};

if (isset($_POST['update'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $update_profile = $pdo->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);

   //manejo de la imagen
   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_img/'.$image;

   if (!empty($image)) {

      if ($image_size > 2000000) {
         $message[] = 'la imagen es muy grande';
      }else {
         $update_image = $pdo->prepare("UPDATE `users` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $user_id]);

         if ($update_image) {
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'la imagen fue actualizada correctamente!';
         }
      }
   }

   $old_pass = $_POST['old_pass'];
   $previous_pass = md5($_POST['previous_pass']);   
   $previous_pass = filter_var($previous_pass, FILTER_SANITIZE_STRING);
   $new_pass = md5($_POST['new_pass']);   
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = md5($_POST['confirm_pass']);   
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if (!empty($previous_pass) || !empty($new_pass) || !empty($confirm_pass)) {
      if ($previous_pass != $old_pass) {
         $message[] = 'la contraseña antigua no coincide';
      }elseif($new_pass != $confirm_pass) {
         $message[] = 'la confirmacion de contraseña no coincide';
      }else {
         $update_password = $pdo->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_password->execute([$confirm_pass, $user_id]);
         $message[] = 'la contraseña fue actualizada!';
      }
   }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Actualizar perfil usuario</title>
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

   <h1 class="title"> actualizar <span>perfil</span> usuario </h1>

   <section class="update-profile-container">
      
      <?php
         $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ? ");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>

      <form action="" method="post" enctype="multipart/form-data">
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <div class="flex">
            <div class="inputBox">
               <span>nombre de usuario: </span>
               <input type="text" name="name" required class="box" placeholder="ingresa tu nombre" value="<?= $fetch_profile['name']; ?>">
               <span>email: </span>
               <input type="email" name="email" required class="box" placeholder="ingresa tu correo" value="<?= $fetch_profile['email']; ?>">
               <span>foto: </span>
               <input type="hidden" name="old_image" value="<?= $fetch_profile['image']; ?>">
               <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
            </div>
            <div class="inputBox">
               <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
               <span>contraseña anterior :</span>
               <input type="password" class="box" name="previous_pass" placeholder="ingrese contraseña antigua">
               <span>nueva contraseña :</span>
               <input type="password" class="box" name="new_pass" placeholder="ingrese nueva contraseña">
               <span>confirmar contraseña :</span>
               <input type="password" class="box" name="confirm_pass" placeholder="confirmar nueva contraseña">
            </div>
         </div>
         <div class="flex-btn">
            <input type="submit" value="actualizar perfil" name="update" class="btn">
            <a href="user_page.php" class="option-btn">volver atras</a>
         </div>
      </form>

   </section> 
   
</body>
</html>
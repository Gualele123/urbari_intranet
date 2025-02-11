<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location:login.php');
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pagina Usuario</title>
   <!-- font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- mi css -->
     <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <h1 class="title"> <span>user</span> profile page</h1>

   <section class="profile-container">
      <?php
         $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ? ");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?> 

      <!-- muestra perfil de usuario -->
      <div class="profile">
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <h3><?= $fetch_profile['name']; ?></h3>
         <a href="user_profile_update.php" class="btn">update profile</a>
         <a href="logout.php" class="delete-btn">logout</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </section> 
   
</body>
</html>
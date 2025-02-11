<?php
         $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ? ");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?> 
<div class='dashboard-contenido'>
    <div class="contenido">
        <h2 style="color: rgb(0, 255, 242); font-size: 2.4rem;">Intranet Clinica Urbari</h2>
        <p style="font-size: 1.4rem;">Tu Portal de Informacion y Recursos</p>
        <a style="color: rgb(0, 255, 242); font-size: 3.4rem; text-align: center;" class="nav-link active">Bienvenido <?= $fetch_profile['name']; ?></a>
    </div>
</div>
<?php
         $select_profile = $pdo->prepare("SELECT * FROM `users` WHERE id = ? ");
         $select_profile->execute([$colaborador_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?> 
<div class='dashboard-contenido'>
    <div class="contenido">
        <h2 style="color: rgb(0, 255, 242); font-size: 2.4rem;">Intranet Clinica Urbari para Colaborador</h2>
        
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">



<div class="container cumpleaneros swiper"> <!--añadido swiper-->
        <div class="slider-wrapper">
            <div class="card-list swiper-wrapper"> <!--añadido swiper-wrapper-->
            <?php 
                  $conexion = mysqli_connect('localhost','root','','user_form4');

                  $sql = "SELECT id, nombre, fechaNacimiento, appaterno, apmaterno FROM empleado WHERE fechaNacimiento BETWEEN '1900-01-01' AND '2025-01-31'";
                  $result = mysqli_query($conexion,$sql);

                  
                  while ($mostrar=mysqli_fetch_array($result)) {
            ?>
                <!-- tarjeta 1 -->
                <div class="card-item swiper-slide"> <!--añadido swiper-slide-->
                    <img src="image/img-1.jpg" alt="user image" class="user-image">
                    <h2 class="user-name"><?php echo $mostrar['nombre'] . ' ' . $mostrar['appaterno'] . ' ' . $mostrar['apmaterno']  ?></h2>
                    <p class="user-profession">Sistema</p>
                    <!-- <p class="user-profession">Semana</p> -->
                    <button class="message-button"><?php echo $mostrar['fechaNacimiento']  ?></button>
                </div>
            <?php } ?>
            </div>

            <!--paginacion -->
            <div class="swiper-pagination"></div>

            <!-- botones de navegacion -->
            <div class="swiper-slide-button swiper-button-prev"></div>
            <div class="swiper-slide-button swiper-button-next"></div>
        </div>
    </div>

    <!-- libreria js externa swiper para carousel -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
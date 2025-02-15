<?php
$dsn = 'mysql:host=localhost;dbname=user_form4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener la fecha de hoy
    $fechaHoy = date('Y-m-d');
    $semanaActual = (int)date('W');
    $semanaProxima = $semanaActual + 1;

    // Función para calcular la semana del año de una fecha
    function calcularSemanaAnio($fecha) {
        $dateTime = new DateTime($fecha);
        return (int)$dateTime->format("W");
    }

    // Consulta para obtener todos los empleados
    $stmtEmpleados = $pdo->prepare('SELECT * FROM empleado');
    $stmtEmpleados->execute();
    $empleados = $stmtEmpleados->fetchAll(PDO::FETCH_ASSOC);

    // Filtrar cumpleañeros del día y de la semana/proxima semana
    $cumpleanerosDia = [];
    $cumpleanerosSemana = [];

    foreach ($empleados as $empleado) {
        $fechaNacimiento = new DateTime($empleado['fechaNacimiento']);
        $semanaCumpleanos = calcularSemanaAnio($fechaNacimiento->format('Y-m-d'));
        $empleado['semana_anio'] = $semanaCumpleanos;

        if ($fechaNacimiento->format('m-d') == date('m-d')) {
            $cumpleanerosDia[] = $empleado;
        }

        if ($semanaCumpleanos == $semanaActual || $semanaCumpleanos == $semanaProxima) {
            $cumpleanerosSemana[] = $empleado;
        }
    }

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
    <!-- <div class="top-section">
        <h1>Cumpleañeros del Día</h1>
        <div id="cumpleaneros-dia" class="card-container"></div>
    </div>
    <div class="bottom-section">
        <h1>Cumpleañeros de la Semana</h1>
        <div id="carousel-semana" class="carousel">

        </div>
    </div> -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<div class="top-section">
        <h1>Cumpleañeros del Día</h1>
        <div id="cumpleaneros-dia" class="card-container"></div>
</div>
<!-- <div class="bottom-section"> -->
        <!-- <h1>Cumpleañeros de la Semana</h1> -->
    <div class="container cumpleaneros swiper"> <!--añadido swiper-->
        <div class="slider-wrapper">
            <div id="carousel-semana" class="card-list swiper-wrapper"> <!--añadido swiper-wrapper-->
                <!-- añadir aqui la card -->
        
            </div>

            <!--paginacion -->
            <div class="swiper-pagination"></div>

            <!-- botones de navegacion -->
            <div class="swiper-slide-button swiper-button-prev"></div>
            <div class="swiper-slide-button swiper-button-next"></div>
        </div>
    </div>
<!-- </div> -->
    

 <!-- libreria js externa swiper para carousel -->
 <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
<script>
    // scripts.js
document.addEventListener('DOMContentLoaded', function() {
    const cumpleanerosDia = <?php echo json_encode($cumpleanerosDia); ?>;
    const cumpleanerosSemana = <?php echo json_encode($cumpleanerosSemana); ?>;

    // Función para crear una card
    function createCard(empleado) {

        // añade un div
        const card = document.createElement('div');
        card.className = 'card';
        
        // añade etiqueta imagen
        const img = document.createElement('img');
        img.src = `fotos/${empleado.id}.jpg`; // Asumiendo que la foto tiene el mismo ID que el empleado
        card.appendChild(img);
        
        // añade un h2
        const nombre = document.createElement('h2');
        nombre.textContent = `${empleado.nombre} ${empleado.appaterno} ${empleado.apmaterno}`;
        card.appendChild(nombre);
        
        // añade un parrafo p con el area
        const area = document.createElement('p');
        area.textContent = `Área: ${empleado.area}`;
        card.appendChild(area);
        
        // añade un parrafo p con semana del año
        const semana = document.createElement('p');
        semana.textContent = `Semana del Año: ${empleado.semana_anio}`;
        card.appendChild(semana);
        
        // añade un parrafo p con fecha de nacimiento
        const fechaNacimiento = document.createElement('p');
        const [anio, mes, dia] = empleado.fechaNacimiento.split('-');
        fechaNacimiento.textContent = `Fecha de Nacimiento: ${dia}/${mes}`;
        card.appendChild(fechaNacimiento);
        
        return card;
    }

    // Función para crear una card carousel
    function createCardCarousel(empleado) {

    // añade un div
    const card = document.createElement('div');
    card.className = 'card-item swiper-slide';

    // añade etiqueta imagen
    const img = document.createElement('img');
    img.src = `fotos/${empleado.id}.jpg`; // Asumiendo que la foto tiene el mismo ID que el empleado
    img.className = 'user-image';
    card.appendChild(img);

    // añade un h2
    const nombre = document.createElement('h2');
    nombre.textContent = `${empleado.nombre} ${empleado.appaterno} ${empleado.apmaterno}`;
    nombre.className = 'user-name';
    card.appendChild(nombre);

    // añade un parrafo p con el area
    const area = document.createElement('p');
    area.textContent = `Área: ${empleado.area}`;
    area.className = 'user-profession';
    card.appendChild(area);

    // añade un parrafo p con semana del año
    const semana = document.createElement('p');
    semana.textContent = `Semana del Año: ${empleado.semana_anio}`;
    semana.className = 'user-profession';
    card.appendChild(semana);

    // añade un parrafo p con fecha de nacimiento
    const fechaNacimiento = document.createElement('button');
    const [anio, mes, dia] = empleado.fechaNacimiento.split('-');
    fechaNacimiento.textContent = `Cumpleaño: ${dia}/${mes}`;
    fechaNacimiento.className = 'message-button';
    card.appendChild(fechaNacimiento);

    return card;
    }



    // Mostrar los cumpleañeros del día
    const cumpleanerosDiaContainer = document.getElementById('cumpleaneros-dia');
    cumpleanerosDia.forEach(empleado => {
        const card = createCard(empleado);
        cumpleanerosDiaContainer.appendChild(card);
    });



    // Mostrar los cumpleañeros de la semana y de la próxima semana
    const carouselSemana = document.getElementById('carousel-semana');
    cumpleanerosSemana.forEach(empleado => {
        const card = createCardCarousel(empleado);
        carouselSemana.appendChild(card);
    });
});

</script>

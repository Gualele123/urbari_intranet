

    <script>
    <?php
    $dsn = 'mysql:host=localhost;dbname=tu_base_de_datos';
    $username = 'tu_usuario';
    $password = 'tu_contraseña';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener la fecha de hoy
        $fechaHoy = new DateTime();
        $fechaHoyStr = $fechaHoy->format('Y-m-d');
        $semanaActual = (int)$fechaHoy->format('W');
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

        // Filtrar y ordenar cumpleañeros
        $cumpleanerosDia = [];
        $cumpleanerosPasados = [];
        $cumpleanerosSemana = [];
        $cumpleanerosProximos = [];

        foreach ($empleados as $empleado) {
            $fechaNacimiento = new DateTime($empleado['fechaNacimiento']);
            $semanaCumpleanos = calcularSemanaAnio($fechaNacimiento->format('Y-m-d'));
            $empleado['semana_anio'] = $semanaCumpleanos;

            // Ajustar el año de la fecha de nacimiento al año actual
            $fechaNacimiento->setDate((int)$fechaHoy->format('Y'), $fechaNacimiento->format('m'), $fechaNacimiento->format('d'));

            // Cumpleañeros del día
            if ($fechaNacimiento->format('m-d') == $fechaHoy->format('m-d')) {
                $cumpleanerosDia[] = $empleado;
            }

            // Filtrar los cumpleañeros de los últimos 3 días
            $intervalo = $fechaHoy->diff($fechaNacimiento)->format('%r%a');
            if ($intervalo >= -3 && $intervalo < 0) {
                $cumpleanerosPasados[] = $empleado;
            }

            // Cumpleañeros de la semana actual y próxima semana
            if ($semanaCumpleanos == $semanaActual || $semanaCumpleanos == $semanaProxima) {
                $cumpleanerosProximos[] = $empleado;
            }
        }

        // Ordenar cumpleañeros
        usort($cumpleanerosPasados, function ($a, $b) {
            return strtotime($a['fechaNacimiento']) - strtotime($b['fechaNacimiento']);
        });

        usort($cumpleanerosProximos, function ($a, $b) {
            return strtotime($a['fechaNacimiento']) - strtotime($b['fechaNacimiento']);
        });

        // Fusionar listas para el carrusel (últimos 3 días, hoy, semana actual y próxima semana)
        $cumpleanerosSemana = array_merge($cumpleanerosPasados, $cumpleanerosDia, $cumpleanerosProximos);

        // Enviar los resultados a JavaScript
        echo "const cumpleanerosDia = " . json_encode($cumpleanerosDia) . ";";
        echo "const cumpleanerosSemana = " . json_encode($cumpleanerosSemana) . ";";

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>
    </script>
    <script src="scripts.js"></script>
</body>
</html>

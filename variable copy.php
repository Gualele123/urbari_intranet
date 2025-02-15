<?php
$dsn = 'mysql:host=localhost;dbname=tu_base_de_datos';
$username = 'tu_usuario';
$password = 'tu_contraseña';

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

    // Filtrar y ordenar cumpleañeros
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

    usort($cumpleanerosSemana, function ($a, $b) {
        return strtotime($a['fechaNacimiento']) - strtotime($b['fechaNacimiento']);
    });

    // Enviar los resultados a JavaScript
    echo "<script>const cumpleanerosDia = " . json_encode($cumpleanerosDia) . ";</script>";
    echo "<script>const cumpleanerosSemana = " . json_encode($cumpleanerosSemana) . ";</script>";

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

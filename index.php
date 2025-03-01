<?php
include 'config.php';

// Verificar si una sesión ya está iniciada antes de iniciar una nueva
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$showUserInactiveAlert = false;
$showRoleInactiveAlert = false;
$showBothInactiveAlert = false;

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Consulta para verificar el usuario y obtener el estado del rol
    $select = $pdo->prepare("SELECT users.*, roles.estado as rol_estado, roles.rol as rol_nombre 
                             FROM users 
                             JOIN roles ON users.rol_id = roles.id 
                             WHERE users.name = ? AND users.password = ?");
    $select->execute([$name, $pass]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($select->rowCount() > 0) {
        // Verificar si el usuario está inactivo
        if ($row['estado'] == 'inactivo') {
            // Verificar si también el rol del usuario está inactivo
            if ($row['rol_estado'] == 'inactivo') {
                // Mostrar mensaje de error si ambos, usuario y rol, están inactivos
                $showBothInactiveAlert = true;
            } else {
                // Mostrar mensaje de error si solo el usuario está inactivo
                $showUserInactiveAlert = true;
            }
        } elseif ($row['rol_estado'] == 'inactivo') {
            // Mostrar mensaje de error si solo el rol está inactivo
            $showRoleInactiveAlert = true;
        } else {
            // Establecer variables de sesión
            $_SESSION['rol'] = $row['rol_nombre'];
            $_SESSION['user_id'] = $row['id'];

            switch ($row['rol_nombre']) {
                case 'admin':
                    header('location:admin_page.php');
                    break;
                case 'user':
                    header('location:user_page.php');
                    break;
                case 'colaborador':
                    header('location:colaborador_page.php');
                    break;
                case 'recursos humanos':
                    header('location:rh_page.php');
                    break;
                default:
                    $message[] = 'Usuario no encontrado!';
            }
        }
    } else {
        $message[] = 'Email o contraseña incorrecta!';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- mi css -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <?php if(isset($message)) { foreach($message as $message) { echo ' <div class="message"> <span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.remove();"></i> </div>'; } } ?>
    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="logo-contenedor"> <img src="./img/logo.png" alt=""> </div>
            <h3>Iniciar Sesion</h3> <input type="text" required placeholder="Ingrese usuario" class="box" name="name">
            <input type="password" required placeholder="Ingrese contraseña" class="box" name="pass">
            <p>No tienes una cuenta? <a href="register.php">Registrarse</a></p> <input type="submit" value="Ingresar" class="btn" name="submit">
        </form>
    </section>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        <?php if($showUserInactiveAlert): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Su cuenta de usuario está inactiva. No tiene permiso para acceder al sistema.'
            });
        <?php endif; ?>

        <?php if($showRoleInactiveAlert): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El rol asociado a su cuenta está inactivo. No tiene permiso para acceder al sistema.'
            });
        <?php endif; ?>

        <?php if($showBothInactiveAlert): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Tanto su cuenta de usuario como el rol asociado están inactivos. No tiene permiso para acceder al sistema.'
            });
        <?php endif; ?>
    </script>
</body>
</html>

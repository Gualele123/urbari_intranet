<?php
include 'config.php';

// Verificar si una sesión ya está iniciada antes de iniciar una nueva
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$showToast = false;

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    // Consulta para verificar el usuario y obtener el estado del rol
    $select = $pdo->prepare("SELECT users.*, roles.estado as rol_estado, roles.rol as rol_nombre 
                             FROM users 
                             JOIN roles ON users.rol_id = roles.id 
                             WHERE users.name = ? AND users.password = ?");
    $select->execute([$name, $pass]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($select->rowCount() > 0) {
        // Verificar si el rol del usuario está activo
        if ($row['rol_estado'] == 'activo') {
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
        } else {
            // Mostrar mensaje de error si el rol está inactivo
            $showToast = true;
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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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

    <!-- Toast Container -->
    <div class="toast-container position-fixed bottom-0 right-0 p-3" style="z-index: 5">
        <div id="toastInactivo" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="mr-auto">Error</strong>
                <small>Ahora</small>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                Su rol está inactivo. No tiene permiso para acceder al sistema.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            <?php if($showToast): ?>
                $('#toastInactivo').toast({ delay: 5000 });
                $('#toastInactivo').toast('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>

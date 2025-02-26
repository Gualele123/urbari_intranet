<?php
include 'config.php';

// Verificar si una sesión ya está iniciada antes de iniciar una nueva
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select = $pdo->prepare("SELECT * FROM `users` WHERE name = ? AND password = ?");
    $select->execute([$name, $pass]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($select->rowCount() > 0) {
        $rol_id = $row['rol_id'];
        $select_role = $pdo->prepare("SELECT * FROM `roles` WHERE id = ?");
        $select_role->execute([$rol_id]);
        $rol = $select_role->fetch(PDO::FETCH_ASSOC)['rol'];

        $_SESSION['rol'] = $rol;
        $_SESSION['user_id'] = $row['id'];

        switch ($rol) {
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
</body>
</html>

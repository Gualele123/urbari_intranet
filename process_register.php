<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = md5($_POST['pass']);
    $cpass = md5($_POST['cpass']);

    if (empty($name) || empty($email) || empty($_POST['pass']) || empty($_POST['cpass']) || empty($_FILES['image']['name'])) {
        echo json_encode(['error' => 'Todos los campos son obligatorios.']);
        exit;
    }

    if ($pass !== $cpass) {
        echo json_encode(['error' => 'Las contraseñas no coinciden.']);
        exit;
    }

    // Verificar si el email ya existe
    $select = $pdo->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);
    if ($select->rowCount() > 0) {
        echo json_encode(['error' => 'El email ya existe.']);
        exit;
    }

    // Verificar el tamaño de la imagen
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_img/'.$image;
    
    if ($image_size > 2000000) {
        echo json_encode(['error' => 'El tamaño de la imagen es muy grande.']);
        exit;
    }

    // Obtener el id del rol 'user'
    $select_role = $pdo->prepare("SELECT id FROM `roles` WHERE rol = 'user'");
    $select_role->execute();
    $role_row = $select_role->fetch(PDO::FETCH_ASSOC);
    if (!$role_row) {
        echo json_encode(['error' => 'El rol de usuario no existe.']);
        exit;
    }
    $rol_id = $role_row['id'];

    // Insertar el nuevo usuario
    $insert = $pdo->prepare("INSERT INTO `users` (name, email, password, rol_id, image) VALUES (?, ?, ?, ?, ?)");
    $insert->execute([$name, $email, $pass, $rol_id, $image]);

    if ($insert) {
        move_uploaded_file($image_tmp_name, $image_folder);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error al registrar el usuario.']);
    }
} else {
    echo json_encode(['error' => 'Método de solicitud no permitido.']);
}
?>

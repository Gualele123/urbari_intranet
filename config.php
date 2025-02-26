<?php
$host = 'localhost';
$db   = 'user_form4';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Conexión exitosa!";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Iniciar la sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener rol_id si no está en la sesión
if (!isset($_SESSION['role_id']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $select_user = $pdo->prepare("SELECT rol_id FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);
    $user = $select_user->fetch(PDO::FETCH_ASSOC);
    $_SESSION['role_id'] = $user['rol_id'];
}
?>

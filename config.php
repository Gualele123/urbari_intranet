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

// function getAllowedModules($pdo, $role_id) {
//     $select_permissions = $pdo->prepare("SELECT modulo FROM `roles_permisos` WHERE role_id = ? AND valor = 1");
//     $select_permissions->execute([$role_id]);
//     return $select_permissions->fetchAll(PDO::FETCH_COLUMN, 0);
// }


try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Conexión exitosa!";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

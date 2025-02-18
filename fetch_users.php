<?php
include 'config.php';

$select_users = $pdo->prepare("SELECT * FROM `users`");
$select_users->execute();

while ($row = $select_users->fetch(PDO::FETCH_ASSOC)) {
    echo "
    <tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['user_type']}</td>
        <td><img src='uploaded_img/{$row['image']}' alt='' width='50'></td>
        <td>
            <button class='btn' onclick=\"editarUsuario({$row['id']}, '{$row['name']}', '{$row['email']}', '{$row['user_type']}', '{$row['image']}')\">Editar</button>
            <button class='btn' onclick=\"eliminarUsuario({$row['id']})\">Eliminar</button>
        </td>
    </tr>
    ";
}
?>

<?php
include 'config.php';

$role_id = $_GET['role_id'];

// Obtener el rol del usuario
$select_role = $pdo->prepare("SELECT * FROM `roles` WHERE id = ?");
$select_role->execute([$role_id]);
$role = $select_role->fetch(PDO::FETCH_ASSOC);

// Si el rol es "admin", otorgar todos los permisos
if ($role['rol'] === 'admin') {
    $modulos = [
        'dashboard',
        'usuarios',
        'roles',
        'cumpleaneros',
        'comunicados',
        'servicios',
        'contactos',
        'formularios'
    ];

    $permisos = [
        'ver',
        'crear',
        'editar',
        'eliminar'
    ];

    // Guardar permisos directamente para el rol de administrador
    foreach ($modulos as $modulo) {
        foreach ($permisos as $permiso) {
            $insert_permiso = $pdo->prepare("INSERT INTO `roles_permisos` (id_rol, id_permiso, modulo, valor) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE valor = VALUES(valor)");
            $select_permiso = $pdo->prepare("SELECT id FROM `permisos` WHERE permiso = ?");
            $select_permiso->execute([$permiso]);
            $result = $select_permiso->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $id_permiso = $result['id'];
                $insert_permiso->execute([$role_id, $id_permiso, $modulo, 1]);
            } else {
                echo "Error: El permiso '$permiso' no existe en la tabla 'permisos'.";
                exit();
            }
        }
    }
} else {
    // Obtener permisos específicos del rol no administrador
    $select_permissions = $pdo->prepare("SELECT rp.modulo, p.permiso, rp.valor FROM `roles_permisos` rp JOIN `permisos` p ON rp.id_permiso = p.id WHERE rp.id_rol = ?");
    $select_permissions->execute([$role_id]);
    $permissions = $select_permissions->fetchAll(PDO::FETCH_ASSOC);

    $modulos = [
        'dashboard',
        'usuarios',
        'roles',
        'cumpleaneros',
        'comunicados',
        'servicios',
        'contactos',
        'formularios'
    ];

    $permisos = [
        'ver',
        'crear',
        'editar',
        'eliminar'
    ];
}
?>

<?php
  include 'header.php';
?>

<?php
  include 'navbar.php';
?>


    <div class="container">
        <h1>Gestionar Permisos para el Rol: <?= $role['rol']; ?></h1>
        <form id="permisosForm" method="post" action="save_permissions.php">
            <table>
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <th>Ver</th>
                        <th>Crear</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modulos as $modulo) { ?>
                    <tr>
                        <td><?= ucfirst($modulo); ?></td>
                        <?php foreach ($permisos as $permiso) { 
                            $permiso_checked = '';
                            if (isset($permissions)) {
                                foreach ($permissions as $p) {
                                    if ($p['modulo'] == $modulo && $p['permiso'] == $permiso && $p['valor'] == 1) {
                                        $permiso_checked = 'checked';
                                    }
                                }
                            }
                        ?>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="<?= $modulo . '_' . $permiso; ?>" <?= $permiso_checked; ?>>
                                <span class="slider"></span>
                            </label>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="role_id" value="<?= $role_id; ?>">
            <button type="submit" class="btn btn-success">Guardar Permisos</button>
            <a href="admin_page.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

<?php
  include 'footer.php';
?>

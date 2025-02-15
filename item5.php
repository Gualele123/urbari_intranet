<?php
include 'config.php';

// Obtener todos los contactos junto con su área
$sql = "SELECT c.id, c.foto, c.nombre, c.contacto, a.nombre AS area 
        FROM contactos c
        LEFT JOIN area a ON c.id_area = a.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="row">
          <div class="col">
          </div>
          <div class="col-10">
          <a class="btn btn-success" href="crearcontacto.php">Agregar Contacto</a>
    <br>
    <h1>Lista de Contactos</h1>
    <table id="myTable" class="table table-striped table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Interno</th>
                <th>Área</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?= $contact['id']; ?></td>
                    <td><img src="ver_imagencontacto.php?id=<?= $contact['id']; ?>" alt="Foto" width="50"></td>
                    <td><?= $contact['nombre']; ?></td>
                    <td><img src="img/icono-whatsapp.png" alt="Foto" width="35"><?= $contact['contacto']; ?></td>
                    <td><?= $contact['area']; ?></td>
                    <td>
                        <a class="btn btn-secondary" href="editarcontacto.php?id=<?= $contact['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a> |
                        <a class="btn btn-danger" href="eliminarcontacto.php?id=<?= $contact['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
          </div>
          <div class="col">
          </div>
</div>



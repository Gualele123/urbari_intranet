<?php
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $area_id = $_POST['area_id'];
    $nombre_archivo = $_FILES['archivo']['name'];
    $ruta_archivo = "uploads/" . basename($nombre_archivo);
    
    if(move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)){
        $sql = "UPDATE archivo SET area_id=:area_id, nombre_archivo=:nombre_archivo, ruta_archivo=:ruta_archivo WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':area_id', $area_id);
        $stmt->bindParam(':nombre_archivo', $nombre_archivo);
        $stmt->bindParam(':ruta_archivo', $ruta_archivo);

        if($stmt->execute()){
            echo "Archivo actualizado correctamente.";
        } else {
            echo "Error al actualizar el archivo.";
        }
    } else {
        echo "Error al subir el archivo.";
    }
}
?>

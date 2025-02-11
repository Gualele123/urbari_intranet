<?php
if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $sql = "DELETE FROM archivo WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);

    if($stmt->execute()){
        echo "Archivo eliminado correctamente.";
    } else {
        echo "Error al eliminar el archivo.";
    }
}
?>

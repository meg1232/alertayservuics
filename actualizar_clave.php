<?php
session_start();
include 'conexion.php'; // Asegúrate de que este archivo establece correctamente la conexión con PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clave_dinamica = $_POST['clave_dinamica'] ?? '';
    $userID = $_SESSION['id'] ?? null; // Obtener el ID del usuario autenticado

    if (!empty($clave_dinamica) && !empty($userID)) {
        try {
            $conn = new Conexion(); // Se usa la clase de conexión
            $db = $conn->ObtenerConexion();

            $sql = "UPDATE usuarios SET clave_dinamica = :clave_dinamica WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":clave_dinamica", $clave_dinamica, PDO::PARAM_STR);
            $stmt->bindParam(":id", $userID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Clave dinámica actualizada correctamente."]);
            } else {
                echo json_encode(["success" => false, "message" => "Error al actualizar la clave dinámica."]);
            }

            $stmt = null; // Liberar la consulta
            $db = null;   // Cerrar la conexión
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Error en la actualización: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Datos inválidos o usuario no autenticado."]);
    }
}
?>

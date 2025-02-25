<?php
session_start(); // Iniciar sesión
include 'conexion.php'; // Asegúrate de que la conexión es correcta

// Asegurar que la solicitud sea PUT
if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    if (!isset($_SESSION['id'])) {
        echo json_encode(["success" => false, "error" => "Usuario no autenticado"]);
        exit;
    }

    $usuarioID = $_SESSION['id'];

    // Obtener los datos enviados en formato JSON
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Verificar si se reciben las tres coordenadas
    if (isset($data['tcoordenada']) && isset($data['tcoordenada2']) && isset($data['tcoordenada3'])) {
        try {
            $conn = new Conexion();
            $db = $conn->ObtenerConexion();

            // Asegúrate de que los nombres de las columnas coincidan con la BD
            $sql = "UPDATE usuarios SET coordenadas = :coordenada1, coordenadas2 = :coordenada2, coordenadas3 = :coordenada3 WHERE id = :id"; // Usar las columnas correctas
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":coordenada1", $data['tcoordenada'], PDO::PARAM_STR);
            $stmt->bindValue(":coordenada2", $data['tcoordenada2'], PDO::PARAM_STR);
            $stmt->bindValue(":coordenada3", $data['tcoordenada3'], PDO::PARAM_STR); // Bind para tcoordenada3
            $stmt->bindValue(":id", $usuarioID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al actualizar"]);
            }

            $stmt = null;
            $db = null;
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Datos incompletos"]);
    }
}
?>

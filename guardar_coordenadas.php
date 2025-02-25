<?php
session_start();
include "conexion.php"; // Archivo de conexión

// Mostrar errores (solo para depuración, luego eliminar)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    echo json_encode(["error" => "No hay sesión activa"]);
    exit();
}

$usuario = $_SESSION['id']; // Usuario en sesión
$coordenada1 = $_POST['coordenada1'] ?? '';
$coordenada2 = $_POST['coordenada2'] ?? '';

if (empty($coordenada1) || empty($coordenada2)) {
    echo json_encode(["error" => "Las coordenadas no pueden estar vacías"]);
    exit();
}

$conn = new Conexion();
$db = $conn->ObtenerConexion(); // Método de conexión

if (!$db) {
    echo json_encode(["error" => "Error en la conexión a la base de datos"]);
    exit();
}

$sql = "UPDATE usuarios SET coordenadas = ?, coordenadas2 = ? WHERE user = ?";
$stmt = $db->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta"]);
    exit();
}

$stmt->bind_param("sss", $coordenada1, $coordenada2, $usuario);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Error al actualizar la base de datos: " . $stmt->error]);
}

$stmt->close();
$db->close();
?>

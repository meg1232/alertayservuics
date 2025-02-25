<?php
session_start();
require 'conexion.php'; // Archivo que contiene la conexión a la base de datos

// Habilitar la visualización de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si hay una sesión activa con id
if (!isset($_SESSION['id'])) {
    echo json_encode(["error" => "No hay sesión activa"]);
    exit();
}

$usuarioID = $_SESSION['id']; // Obtener el ID del usuario de la sesión
$conexion = new Conexion();
$conn = $conexion->ObtenerConexion();

// Preparar la consulta para obtener las coordenadas del usuario
$stmt = $conn->prepare("SELECT tcoordenada, tcoordenada2, tcoordenada3 FROM usuarios WHERE id = ?"); // Incluir tcoordenada3
$stmt->execute([$usuarioID]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Verificar si se encontraron coordenadas
if ($row) {
    echo json_encode($row); // Retornar las coordenadas en formato JSON
} else {
    echo json_encode(["tcoordenada" => "", "tcoordenada2" => "", "tcoordenada3" => ""]); // Retornar vacío si no se encuentra
}
?>
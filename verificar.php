<?php
session_start();
require 'conexion.php'; // Archivo que contiene la conexión a la base de datos

// Verificar si hay una sesión activa con id
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Acceso no autorizado'); window.location.href='index.html';</script>";
    exit();
}

$usuarioID = $_SESSION['id']; // Obtener el ID del usuario de la sesión

// Consultar el destino del usuario en la base de datos
$conn = new Conexion();
$db = $conn->ObtenerConexion();
$sql = "SELECT destino FROM usuarios WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$usuarioID]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if ($resultado) {
    $destino = $resultado['destino']; // Obtener el destino del usuario

    switch ($destino) {
        case 1:
            header("Location: index2.HTML"); // Redirigir a index2.html si el destino es 1
            exit();
        case 2:
            header("Location: index3.HTML"); // Redirigir a index3.html si el destino es 2
            exit();
        default:
            // Redirigir a index.html y agregar un mensaje de error
            header("Location: index.php?error=1"); // Enviar parámetro de error
            exit();
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.location.href='index.php';</script>";
    exit();
}
?>

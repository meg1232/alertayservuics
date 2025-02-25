<?php
session_start();
require_once 'conexion.php';

// Crear instancia de conexión
$conexion = new Conexion();
$conn = $conexion->obtenerConexion();

// Configuración de Telegram
$chat_id = "-1002267762294"; // Asegúrate de incluir el signo negativo
$bot_token = "8038367240:AAFwLaUBcYyUMFzNlTLLO2c0DAEVqBNraLI"; // Token del bot

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    try {
        // Verificar si el usuario ya existe
        $stmt = $conn->prepare("SELECT id, clave FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);

        if ($stmt->rowCount() > 0) {
            // Usuario existente, intentar iniciar sesión
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($password === $row['clave']) {
                $_SESSION['id'] = $row['id'];
                echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso", "usuarioID" => $row['id']]);
                exit();
            } else {
                echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
                exit();
            }
        } else {
            // Usuario no existe, proceder a registrar
            $destino = 0;
            $stmt = $conn->prepare("INSERT INTO usuarios (usuario, clave, destino) VALUES (?, ?, ?)");
            $stmt->execute([$usuario, $password, $destino]);

            $userID = $conn->lastInsertId();

            // Obtener el valor actual de admin1
            $stmt = $conn->prepare("SELECT current_value FROM admin_counter LIMIT 1");
            $stmt->execute();
            $currentValue = $stmt->fetchColumn();

            // Determinar el siguiente admin en la secuencia (1 → 2 → 3 → 1)
            $admin = ($currentValue % 3) + 1;

            $stmt = $conn->prepare("UPDATE usuarios SET admin1 = ? WHERE id = ?");
            $stmt->execute([$admin, $userID]);

            $stmt = $conn->prepare("UPDATE admin_counter SET current_value = ?");
            $stmt->execute([$admin]);

            // Nombres de administradores
            $adminNombres = [
                1 => "Aurelio",
                2 => "Webo",
                3 => "José Luis"
            ];
            $adminNombre = $adminNombres[$admin] ?? "Desconocido";

           // Preparar mensaje de Telegram (formato MarkdownV2)
$mensajeTelegram = "📢 *Nuevo Usuario Registrado* 📢\n\n"
    . "👤 *Usuario:* `$usuario`\n"
    . "🆔 *ID:* `$userID`\n"
    . "🔑 *Admin:* `$adminNombre`\n\n"
    . "🚀 _Sistema desarrollado por_ *MegabyteAG5* 💻🔥";

// Enviar mensaje a Telegram
$url = "https://api.telegram.org/bot$bot_token/sendMessage?" . http_build_query([
    "chat_id" => $chat_id,
    "text" => $mensajeTelegram,
    "parse_mode" => "MarkdownV2"
]);


            $response = file_get_contents($url);

            // Si hay error, registrarlo en logs
            if ($response === false) {
                error_log("Error al enviar mensaje a Telegram: " . print_r(error_get_last(), true));
            }

            // Insertar notificación en la base de datos
            $mensaje = "Nuevo usuario, verifiquen recargando " . $usuario;
            $stmt = $conn->prepare("INSERT INTO notificaciones (mensaje) VALUES (?)");
            $stmt->execute([$mensaje]);

            $_SESSION['id'] = $userID;
            echo json_encode(["success" => true, "message" => "Registro exitoso y sesión iniciada", "usuarioID" => $userID]);
            exit();
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
        exit();
    }
}
?>

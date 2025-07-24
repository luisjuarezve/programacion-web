<?php
session_start();
require_once '../bdd/conexion.php';

if (isset($_SESSION['usuario_id'])) {
  $usuario_id = $_SESSION['usuario_id'];

  // Guardar respuestas si existen
  if (isset($_POST['respuestas'])) {
    $respuestas = json_decode($_POST['respuestas'], true);

    foreach ($respuestas as $respuesta) {
      $pregunta_id = $respuesta['pregunta_id'];
      $a = isset($respuesta['a']) ? intval($respuesta['a']) : 0;
      $b = isset($respuesta['b']) ? intval($respuesta['b']) : 0;
      $respuesta_usuario = $respuesta['respuesta'];
      $correcta = $respuesta['correcta'] ? 1 : 0;

      // Usar ON DUPLICATE KEY UPDATE para evitar error de clave única
      $stmt = $conn->prepare("
        INSERT INTO respuestas_usuarios (usuario_id, pregunta_id, a, b, respuesta, correcta)
        VALUES (?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
          respuesta = VALUES(respuesta),
          correcta = VALUES(correcta),
          a = VALUES(a),
          b = VALUES(b),
          fecha_respuesta = CURRENT_TIMESTAMP
      ");
      $stmt->bind_param("iiiisi", $usuario_id, $pregunta_id, $a, $b, $respuesta_usuario, $correcta);
      $stmt->execute();
    }
  }
}

session_destroy();

// Guardar estado para mostrar mensaje en el login
session_start();
$_SESSION['logout_success'] = true;

// Redirigir al login
header("Location: ../index.php");
exit;
?>

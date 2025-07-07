<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

require_once 'bdd/conexion.php';
$usuario_id = $_SESSION['usuario_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['nivel_desbloqueado'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Falta nivel_desbloqueado']);
  exit;
}

$nuevo_nivel = intval($data['nivel_desbloqueado']);
// Intentar actualizar primero
$stmt = $conn->prepare("UPDATE progreso_niveles SET nivel_desbloqueado = ?, fecha_actualizacion = NOW() WHERE usuario_id = ?");
$stmt->bind_param("ii", $nuevo_nivel, $usuario_id);
$stmt->execute();

if ($stmt->affected_rows === 0) {
  // Si no existía, insertar
  $stmt = $conn->prepare("INSERT INTO progreso_niveles (usuario_id, nivel_desbloqueado, fecha_actualizacion) VALUES (?, ?, NOW())");
  $stmt->bind_param("ii", $usuario_id, $nuevo_nivel);
  $stmt->execute();
}

echo json_encode(['ok' => true]);

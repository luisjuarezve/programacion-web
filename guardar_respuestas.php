<?php
session_start();
require_once 'bdd/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$data = json_decode(file_get_contents('php://input'), true);

$nivel = $data['nivel'] ?? null;
$respuestas = $data['respuestas'] ?? [];

if (!$nivel || !is_array($respuestas)) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

foreach ($respuestas as $respuesta) {
    $pregunta_id = $respuesta['pregunta_id'];
    $a = isset($respuesta['a']) ? intval($respuesta['a']) : 0;
    $b = isset($respuesta['b']) ? intval($respuesta['b']) : 0;
    $respuesta_usuario = $respuesta['respuesta'];
    $correcta = $respuesta['correcta'] ? 1 : 0;

    // Usar ON DUPLICATE KEY UPDATE para actualizar si ya existe
    $stmt = $conn->prepare("INSERT INTO respuestas_usuarios (usuario_id, nivel, pregunta_id, a, b, respuesta, correcta) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE respuesta = VALUES(respuesta), correcta = VALUES(correcta), a = VALUES(a), b = VALUES(b), fecha_respuesta = CURRENT_TIMESTAMP");
    $stmt->bind_param("iiiiisi", $usuario_id, $nivel, $pregunta_id, $a, $b, $respuesta_usuario, $correcta);
    $stmt->execute();
}

echo json_encode(['success' => true]);

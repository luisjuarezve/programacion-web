<?php
session_start();
require_once 'bdd/conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener respuestas guardadas (sin niveles)
$stmt = $conn->prepare("SELECT pregunta_id, a, b, respuesta, correcta FROM respuestas_usuarios WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$respuestas = [];
while ($row = $res->fetch_assoc()) {
    $respuestas[] = $row;
}

echo json_encode([
    'respuestas' => $respuestas
]);

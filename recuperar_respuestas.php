<?php
session_start();
require_once 'bdd/conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener nivel desbloqueado
$stmt = $conn->prepare("SELECT nivel_desbloqueado FROM progreso_niveles WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$progreso = $result->fetch_assoc();
$nivel_desbloqueado = $progreso ? $progreso['nivel_desbloqueado'] : 1;

// Obtener respuestas guardadas
$stmt = $conn->prepare("SELECT nivel, pregunta_id, a, b, respuesta, correcta FROM respuestas_usuarios WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();
$respuestas = [];
while ($row = $res->fetch_assoc()) {
    $respuestas[] = $row;
}

echo json_encode([
    'nivel_desbloqueado' => $nivel_desbloqueado,
    'respuestas' => $respuestas
]);

<?php
session_start();
require_once 'bdd/conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$usuario_id = $_SESSION['usuario_id'];
$nivel = isset($data['nivel']) ? intval($data['nivel']) : null;

if ($nivel === null) {
    echo json_encode(['error' => 'Nivel no especificado']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM respuestas_usuarios WHERE usuario_id = ? AND nivel = ?");
$stmt->bind_param("ii", $usuario_id, $nivel);
$stmt->execute();

if ($stmt->affected_rows >= 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'No se pudo eliminar']);
}

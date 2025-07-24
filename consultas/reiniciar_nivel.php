<?php
session_start();
require_once 'bdd/conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}


$usuario_id = $_SESSION['usuario_id'];
$stmt = $conn->prepare("DELETE FROM respuestas_usuarios WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

if ($stmt->affected_rows >= 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'No se pudo eliminar']);
}

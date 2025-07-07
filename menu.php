<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}

// Conexión a la base de datos
require_once 'bdd/conexion.php';

$usuario_id = $_SESSION['usuario_id'];
$nombre_usuario = $_SESSION['usuario'];

// Obtener el nivel desbloqueado del usuario
$stmt = $conn->prepare("SELECT nivel_desbloqueado FROM progreso_niveles WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$progreso = $result->fetch_assoc();
$nivel_desbloqueado = $progreso ? $progreso['nivel_desbloqueado'] : 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú de Ejercicios</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <header class="navbar">
    <div class="navbar-left">👋 Bienvenido, <?= htmlspecialchars($nombre_usuario); ?></div>
    <div class="navbar-right">
      <form method="POST" action="logout.php">
        <button type="submit" class="cerrar-sesion">Cerrar sesión</button>
      </form>
    </div>
  </header>

  <div class="contenedor-menu">
    <h2 class="titulo">Selecciona un nivel</h2>
    <div class="grid-niveles">
      <?php for ($i = 1; $i <= 8; $i++): ?>
        <?php if ($i <= $nivel_desbloqueado): ?>
          <a href="#" class="nivel" data-nivel="<?= $i ?>">Nivel <?= $i ?></a>
        <?php else: ?>
          <span class="nivel bloqueado">Nivel <?= $i ?></span>
        <?php endif; ?>
      <?php endfor; ?>
    </div>
  </div>
  <script src="js/script.js"></script>
</body>
</html>

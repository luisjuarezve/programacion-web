<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Menú de Ejercicios</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <h2 class="titulo">Bienvenido, <?= $_SESSION['usuario']; ?> 👋</h2>

  <div id="ejercicios-container" class="ejercicios-grid"></div>

  <button id="reiniciar-btn">🔄 Reiniciar Ejercicios</button>

  <script src="js/script.js"></script>
</body>
</html>

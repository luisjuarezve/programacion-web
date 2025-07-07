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
  <header class="navbar">
    <div class="navbar-left">👋 Bienvenido, <?= $_SESSION['usuario']; ?></div>
    <div class="navbar-right">
      <form method="POST" action="logout.php">
        <button type="submit" class="cerrar-sesion">Cerrar sesión</button>
      </form>
    </div>
  </header>

  <div class="contenedor-menu">
    <h2 class="titulo">Selecciona un nivel</h2>
    /*
      niveles
    */
</div>
  <script src="js/script.js"></script>
</body>
</html>

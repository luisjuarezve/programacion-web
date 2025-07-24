<?php
  session_start();
  if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
  }

  // Conexión a la base de datos
  require_once 'bdd/conexion.php';

  $usuario_id = $_SESSION['usuario_id'];
  $nombre_usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Menú de Ejercicios</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body class="body-menu">
  <header class="navbar">
    <div class="navbar-left">¡Bienvenido, <?= htmlspecialchars($nombre_usuario); ?>!</div>
    <div class="navbar-right">
      <form method="POST" action="consultas/logout.php" id="logoutForm">
        <input type="hidden" name="respuestas" id="respuestasInput">
        <button type="submit" class="cerrar-sesion">Cerrar</button>
      </form>
    </div>
  </header>

  <div class="contenedor-menu">

  </div>
  <script src="js/script.js"></script>
</body>

</html>
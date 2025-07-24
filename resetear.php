<?php
require 'consultas/reset.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer contraseña</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="body-form">
  <?= $alerta_js ?>
  <div id="mensaje-modal" class="modal">
    <div class="modal-contenido">
      <p id="mensaje-texto"></p>
      <button onclick="cerrarModal()">Aceptar</button>
    </div>
  </div>
  <?php if ($usuario_id && !$exito): ?>
  <form method="POST" class="formulario-recuperar">
    <input type="password" name="contrasena" placeholder="Nueva contraseña" required>
    <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
    <button class="button-submit" type="submit">Restablecer contraseña</button>
    <p style="text-align: center; margin-top: 10px;">
      <a href="index.php" style="color: #4F88FF; font-weight: bold;">
        ← Volver al inicio de sesión
      </a>
    </p>
  </form>
  <?php endif; ?>
  <script>
    function mostrarModal(texto) {
      document.getElementById("mensaje-texto").innerText = texto;
      document.getElementById("mensaje-modal").style.display = "flex";
    }
    function cerrarModal() {
      document.getElementById("mensaje-modal").style.display = "none";
      <?php if ($exito): ?>
        window.location.href = "index.php";
      <?php endif; ?>
    }
  </script>
</body>
</html>

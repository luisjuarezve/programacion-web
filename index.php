<?php
require 'loguear.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="body-form">
  <?php if ($mostrar_toast_logout): ?>
    <div id="toast" class="toast">
      <span>Sesión cerrada correctamente</span>
    </div>
  <?php endif; ?>
  <form method="POST" class="formulario-login">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <button class="button-submit" type="submit">Ingresar</button>
    <p style="text-align: center; margin-top: 10px;">
      ¿No tienes una cuenta?
      <a href="formulario_registro.php" style="color: #4F88FF; font-weight: bold;">Regístrate aquí</a>
    </p>
  </form>
  <script src="js/toast.js"></script>
</body>

</html>
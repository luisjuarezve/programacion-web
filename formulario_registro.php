<?php
require 'registro.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Registrarse</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body class="body-form">
  <?php if (!empty($mensaje))
    echo $mensaje; ?>
  <form method="POST" class="formulario-registro">
    <input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo htmlspecialchars($nombre); ?>"
      required>
    <input type="email" name="email" placeholder="Correo electrónico" value="<?php echo htmlspecialchars($email); ?>"
      required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
    <div class="center">
      <button class="button-submit btn-small" type="submit">Registrarse</button>
      <a href="index.php" class="button-regresar btn-small">Volver</a>
    </div>
  </form>
  <script src="js/toast.js"></script>
</body>

</html>
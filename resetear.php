<?php
require 'bdd/conexion.php';

$token = $_GET['token'] ?? '';
$mostrar_formulario = false;
$mostrar_toast_error = false;
$mostrar_toast_exito = false;

// Verificar si el token es válido
if (!empty($token)) {
  $stmt = $conn->prepare("SELECT id, token_expira FROM usuarios WHERE token_recuperacion = ?");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $fecha_expira = strtotime($usuario['token_expira']);

    if ($fecha_expira > time()) {
      $mostrar_formulario = true;

      // Procesar el envío del formulario
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nueva = trim($_POST['nueva'] ?? '');

        if (!empty($nueva)) {
          // Guardar nueva contraseña (usa hash en producción)
          $contrasena_hash = password_hash($nueva, PASSWORD_DEFAULT);

          $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ?, token_recuperacion = NULL, token_expira = NULL WHERE id = ?");
          $stmt->bind_param("ssi", $contrasena_hash, $usuario['id']);
          $stmt->execute();

          $mostrar_toast_exito = true;
          $mostrar_formulario = false;
        }
      }
    } else {
      $mostrar_toast_error = true; // Token expirado
    }
  } else {
    $mostrar_toast_error = true; // Token no encontrado
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear nueva contraseña</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body class="body-form">
  <?php if ($mostrar_toast_exito): ?>
    <div id="toast" class="toast">
      <span>Tu contraseña fue actualizada correctamente</span>
    </div>
  <?php elseif ($mostrar_toast_error): ?>
    <div id="toast" class="toast">
      <span>El enlace ha expirado o es inválido</span>
    </div>
  <?php endif; ?>

  <?php if ($mostrar_formulario): ?>
    <form method="POST" class="formulario-login">
      <!-- 🖼️ Cinta superior con texto Recuperar -->
      <img src="img/Recupera.png" alt="Recuperar" style="width: 100%; max-width: 380px; display: block; margin: 0 auto 10px;">

      <input type="password" name="nueva" placeholder="Nueva contraseña" required>
      <button class="button-submit" type="submit">Actualizar contraseña</button>

      <p style="text-align: center; margin-top: 10px;">
        <a href="index.php" style="color: #4F88FF; font-weight: bold;">
          ← Volver al inicio
        </a>
      </p>
    </form>
  <?php endif; ?>

  <script src="js/toast.js"></script>
</body>
</html>

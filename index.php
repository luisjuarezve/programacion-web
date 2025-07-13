<?php
session_start();
require 'bdd/conexion.php';
$mostrar_toast_logout = false;
// Mostrar toast si la sesión fue cerrada
if (isset($_SESSION['logout_success'])) {
  $mostrar_toast_logout = true;
  unset($_SESSION['logout_success']);
}
// Procesar login si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $contrasena = $_POST['contrasena'];
  $stmt = $conn->prepare("SELECT id, nombre, email, contrasena FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $resultado = $stmt->get_result();
  if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    if (password_verify($contrasena, $usuario['contrasena'])) {
      $_SESSION['usuario'] = $usuario['nombre'];
      $_SESSION['usuario_id'] = $usuario['id'];
      echo '
        <div id="toast" class="toast">
          <span>Bienvenido, ' . htmlspecialchars($usuario['nombre']) . '</span>
        </div>
      ';
    } else {
      echo '
        <div id="toast-error" class="toast-error">
          <span>Contraseña incorrecta.</span>
        </div>
      ';
    }
  } else {
    echo '
      <div id="toast-error" class="toast-error">
        <span>Correo no registrado.</span>
      </div>
    ';
  }
}
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
  <form method="POST" class="formulario">
    <h2 class="titulo">Ingreso a la Plataforma</h2>
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <button type="submit">Ingresar</button>
    <p style="text-align: center; margin-top: 10px;">
      ¿No tienes una cuenta?
      <a href="register.php" style="color: #4F88FF; font-weight: bold;">Regístrate aquí</a>
    </p>
  </form>
  <script src="js/toast.js"></script>
</body>
</html>
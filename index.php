<?php
session_start();
require 'bdd/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $contrasena = $_POST['contrasena'];

  $stmt = $conn->prepare("SELECT id, nombre, contrasena FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    if (password_verify($contrasena, $usuario['contrasena'])) {
      $_SESSION['usuario'] = $usuario['nombre'];
      echo '
        <div id="toast" class="toast">
          <span>Bienvenido, ' . $usuario['nombre'] . '</span>
          // Redirigiendo a la página principal...
        </div>
        <script>
          window.addEventListener("DOMContentLoaded", function() {
            const toast = document.getElementById("toast");
            toast.classList.add("show");
            setTimeout(() => {
              toast.classList.remove("show");
            }, 3000);
          });
        </script>
      ';
    } else {
      echo '
        <div id="toast-error" class="toast-error">
          <span>Contraseña incorrecta.</span>
        </div>
        <script>
          window.addEventListener("DOMContentLoaded", function() {
            const toast = document.getElementById("toast-error");
            toast.classList.add("show");
            setTimeout(() => {
              toast.classList.remove("show");
            }, 3000);
          });
        </script>
      ';
    }
  } else {
    echo '
      <div id="toast-error" class="toast-error">
        <span>Correo no registrado.</span>
      </div>
      <script>
        window.addEventListener("DOMContentLoaded", function() {
          const toast = document.getElementById("toast-error");
          toast.classList.add("show");
          setTimeout(() => {
            toast.classList.remove("show");
          }, 3000);
        });
      </script>
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
<body>
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
</body>
</html>

<?php
require 'bdd/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = $_POST['nombre'];
  $email = $_POST['email'];
  $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

  // Verificar si el correo ya existe
  $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
  $stmt_check->bind_param("s", $email);
  $stmt_check->execute();
  $stmt_check->store_result();

  if ($stmt_check->num_rows > 0) {
    // Mensaje: el correo ya está registrado
    echo '
      <div id="toast-error" class="toast-error">
        <span>Este correo ya está registrado. Intenta con otro.</span>
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
  } else {
    // Insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre, $email, $contrasena);

    if ($stmt->execute()) {
      echo '
        <div id="toast" class="toast">
          <span>¡Registro exitoso!</span>
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
          <span>Error: ' . $stmt->error . '</span>
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
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrarse</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <form method="POST" class="formulario">
    <h2 class="titulo">Registro Infantil</h2>
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <button type="submit">Registrarse</button>
    <a href="index.php" class="button-regresar">Volver</a>
    </form>

</body>
</html>

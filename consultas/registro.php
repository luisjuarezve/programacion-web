<?php
require 'bdd/conexion.php';

$nombre = "";
$email = "";
$mensaje = "";
$limpiarPasswords = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = $_POST['nombre'];
  $email = $_POST['email'];
  $contrasena = $_POST['contrasena'];
  $confirmar = $_POST['confirmar'];

  // Validar longitud mínima
  if (strlen($contrasena) < 6) {
    $mensaje = '
      <div id="toast-error" class="toast-error">
        <span>La contraseña debe tener al menos 6 caracteres.</span>
      </div>
    ';
  } elseif ($contrasena !== $confirmar) {
    $mensaje = '
      <div id="toast-error" class="toast-error">
        <span>Las contraseñas no coinciden. Por favor, intenta de nuevo.</span>
      </div>
    ';
  } else {
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
      $mensaje = '
        <div id="toast-error" class="toast-error">
          <span>Este correo ya está registrado. Intenta con otro.</span>
        </div>
      ';
    } else {
      $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $nombre, $email, $hash);

      if ($stmt->execute()) {
        $mensaje = '
          <div id="toast" class="toast">
            <span>¡Registro exitoso!</span>
          </div>         
        ';
        // Limpiar variables
        $nombre = "";
        $email = "";
      } else {
        $mensaje = '
          <div id="toast-error" class="toast-error">
            <span>Error: ' . $stmt->error . '</span>
          </div>
        ';
      }
    }
  }
}
?>

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

  if ($contrasena !== $confirmar) {
    $mensaje = '
      <div id="toast-error" class="toast-error">
        <span>Las contraseñas no coinciden. Por favor, intenta de nuevo.</span>
      </div>
      <script>
        window.addEventListener("DOMContentLoaded", function() {
          const toast = document.getElementById("toast-error");
          toast.classList.add("show");
          setTimeout(() => {
            toast.classList.remove("show");
          }, 3000);

          // Limpiar campos de contraseña
          document.querySelector("input[name=\'contrasena\']").value = "";
          document.querySelector("input[name=\'confirmar\']").value = "";
        });
      </script>
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
      $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contrasena) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $nombre, $email, $hash);

      if ($stmt->execute()) {
        $mensaje = '
          <div id="toast" class="toast">
            <span>¡Registro exitoso!</span>
          </div>
          <script>
            window.addEventListener("DOMContentLoaded", function() {
              const toast = document.getElementById("toast");
              toast.classList.add("show");
              setTimeout(() => {
                toast.classList.remove("show");
                window.location.href = "index.php";
              }, 3000);
            });
          </script>
        ';
        // Limpiar variables
        $nombre = "";
        $email = "";
      } else {
        $mensaje = '
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
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrarse</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="body-form">
  <?php if (!empty($mensaje)) echo $mensaje; ?>
  <form method="POST" class="formulario">
    <h2 class="titulo">Registro Infantil</h2>
    <input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo htmlspecialchars($nombre); ?>" required>
    <input type="email" name="email" placeholder="Correo electrónico" value="<?php echo htmlspecialchars($email); ?>" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
    <button type="submit">Registrarse</button>
    <a href="index.php" class="button-regresar">Volver</a>
  </form>
</body>
</html>

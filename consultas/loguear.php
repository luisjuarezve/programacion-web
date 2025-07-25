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

      echo '<script>
  window.onload = () => {
    mostrarVentanaMensaje("Bienvenido, ' . htmlspecialchars($usuario['nombre']) . '", false, () => {
      window.location.href = "menu.php";
    });
  };
</script>';


    } else {
      echo '<script>
        window.onload = () => {
          mostrarVentanaMensaje("Contraseña incorrecta.", true);
        };
      </script>';
    }
  } else {
    echo '<script>
      window.onload = () => {
        mostrarVentanaMensaje("Correo no registrado.", true);
      };
    </script>';
  }
}
?>

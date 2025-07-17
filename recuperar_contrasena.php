<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'bdd/conexion.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
require 'libs/PHPMailer/src/Exception.php';

$token_generado = '';
$alerta_js = '';

function generarToken($longitud = 32)
{
  return bin2hex(random_bytes($longitud / 2));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');

  if (!empty($email)) {
    // Verificar usuario
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
      $usuario = $resultado->fetch_assoc();
      $token_generado = generarToken();
      $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

      // Guardar en recuperaciones_contrasena
      $stmt = $conn->prepare("INSERT INTO recuperaciones_contrasena (usuario_id, token, expira) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $usuario['id'], $token_generado, $expira);
      $stmt->execute();

      // Enviar correo con PHPMailer
      $mail = new PHPMailer(true);

      try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sistemavu1@gmail.com';
        $mail->Password = 'lmzculazogabbsqb'; // contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('sistemavu1@gmail.com', 'Soporte Infantil');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // <-- Agrega esta línea

        $mail->Subject = 'Tu código mágico de recuperación ✨';
        $mail->Body = "
          <h3>¡Hola! 😊</h3>
          <p>Este es tu código para recuperar tu contraseña:</p>
          <h2 style='color: #4F88FF;'>$token_generado</h2>
          <p>Pégalo en la casilla del verificador.</p>
        ";

        $mail->send();

        $alerta_js = "<script>
          window.onload = () => {
            mostrarModal('¡Listo! Revisa tu correo para recuperar tu contraseña');
          };
        </script>";

        // 🚀 Redirigir al verificador
        // header("Location: verificar_codigo.php?email=" . urlencode($email));
        // exit;

      } catch (Exception $e) {
        $errorMsg = addslashes($mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
        $alerta_js = "<script>
          window.onload = () => {
            mostrarModal('Error: $errorMsg');
          };
        </script>";
      }
    } else {
      $alerta_js = "<script>
        window.onload = () => {
          mostrarModal('Ese correo no está registrado');
        };
      </script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Recuperar contraseña</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    .modal-contenido {
      background-color: #FFCCCC;
      padding: 20px;
      border-radius: 12px;
      text-align: center;
      width: 300px;
      animation: aparecer 0.4s ease-in-out;
      box-shadow: 0 0 10px #0004;
    }

    .modal-contenido button {
      background-color: #4F88FF;
      color: white;
      border: none;
      padding: 8px 16px;
      font-weight: bold;
      border-radius: 8px;
      margin-top: 12px;
      cursor: pointer;
    }

    @keyframes aparecer {
      from {
        opacity: 0;
        transform: scale(0.9);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
</head>

<body class="body-form">
  <?= $alerta_js ?>
  <div id="mensaje-modal" class="modal">
    <div class="modal-contenido">
      <p id="mensaje-texto"></p>
      <button onclick="cerrarModal()">Aceptar</button>
    </div>
  </div>
  <form method="POST" class="formulario-recuperar">
    <input type="email" name="email" placeholder="Ingresa tu correo" required>
    <button class="button-submit" type="submit">Enviar instrucciones</button>
    <p style="text-align: center; margin-top: 10px;">
      <a href="index.php" style="color: #4F88FF; font-weight: bold;">
        ← Volver al inicio de sesión
      </a>
    </p>
  </form>
  <script>
    function mostrarModal(texto) {
      document.getElementById("mensaje-texto").innerText = texto;
      document.getElementById("mensaje-modal").style.display = "flex";
    }

    function cerrarModal() {
      document.getElementById("mensaje-modal").style.display = "none";
      // Redirigir al verificador con el email
      <?php if (!empty($email) && isset($usuario)) : ?>
        window.location.href = "verificar_codigo.php?email=<?= urlencode($email) ?>";
      <?php endif; ?>
    }
  </script>
</body>

</html>
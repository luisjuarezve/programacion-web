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
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
      $usuario = $resultado->fetch_assoc();
      $token_generado = generarToken();
      $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

      $stmt = $conn->prepare("INSERT INTO recuperaciones_contrasena (usuario_id, token, expira) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $usuario['id'], $token_generado, $expira);
      $stmt->execute();

      $mail = new PHPMailer(true);

      try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sistemavu1@gmail.com';
        $mail->Password = 'lmzculazogabbsqb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('sistemavu1@gmail.com', 'Soporte Infantil');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

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
            mostrarVentanaMensaje('¡Listo! Revisa tu correo para recuperar tu contraseña', false, () => {
              window.location.href = 'formulario_verificar_codigo.php?email=" . urlencode($email) . "';
            });
          };
        </script>";
      } catch (Exception $e) {
        $errorMsg = addslashes($mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
        $alerta_js = "<script>
          window.onload = () => {
            mostrarVentanaMensaje('Error: $errorMsg', true);
          };
        </script>";
      }
    } else {
      $alerta_js = "<script>
        window.onload = () => {
          mostrarVentanaMensaje('Ese correo no está registrado', true);
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
    .body-form {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background-image: url('../img/BG.png');
      background-size: cover;
      background-position: center;
      height: 100vh;
      color: #333;
    }

    .contenedor-recuperar {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: -40px;
    }

    .titulo-recuperar {
      font-size: 2.8em;
      font-weight: bold;
      color: white;
      font-family: 'comic sans ms', sans-serif;
      margin-bottom: 20px;
      text-align: center;
    }

    .formulario-recuperar {
      background-color: rgba(10, 59, 6, 0.6);
      padding: 100px 60px 20px 60px;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      width: 450px;
      height: 300px;
    }

    input {
      padding: 10px;
      border: 2px solid #CCE0FF;
      border-radius: 6px;
    }

    .button-submit {
      background-image: url('../img/btn normal.png');
      background-size: 200px 40px;
      background-position: center;
      background-repeat: no-repeat;
      background-color: transparent;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      font-family: 'comic sans ms', sans-serif;
    }

    .button-submit:hover {
      background-image: url('../img/btn hover.png');
      background-size: 200px 40px;
      margin-bottom: -1px;
      margin-right: 2px;
      padding: 9px;
      font-size: 18px;
    }
  </style>
</head>
<body class="body-form">
  <?= $alerta_js ?>

  <div class="contenedor-recuperar">
    <h1 class="titulo-recuperar">Recuperar contraseña</h1>

    <form method="POST" class="formulario-recuperar">
      <input type="email" name="email" placeholder="Ingresa tu correo" required>
      <button class="button-submit" type="submit">Enviar instrucciones</button>
      <p style="text-align: center; margin-top: 10px;">
        <a href="index.php" style="color: #4cd137; font-weight: bold;">
          ← Volver al inicio de sesión
        </a>
      </p>
    </form>
  </div>

  <script>
    function mostrarVentanaMensaje(mensaje, esError = false, callback = null) {
      const overlay = document.createElement('div');
      overlay.className = 'popup-overlay popup-abrir';
      overlay.innerHTML = `
        <div class="popup-content" style="max-width:340px;padding:32px 24px;">
          <div style="font-size:1.25em;font-weight:bold;margin-bottom:12px;${esError ? 'color:#d32f2f;' : 'color:#333;'}">
            ${esError ? 'Error' : 'Mensaje'}
          </div>
          <div style="font-size:1.1em;margin-bottom:18px;">${mensaje}</div>
          <button class="btn-cerrar-popup" style="background:#1976d2;color:#fff;border:none;border-radius:8px;padding:8px 24px;font-size:1em;cursor:pointer;">Aceptar</button>
        </div>
      `;
      document.body.appendChild(overlay);
      overlay.querySelector('.btn-cerrar-popup').addEventListener('click', () => {
        overlay.classList.add('popup-cerrar');
        setTimeout(() => {
          overlay.remove();
          if (callback) callback();
        }, 350);
      });
    }
  </script>
</body>
</html>

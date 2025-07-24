<?php
require 'bdd/conexion.php';
$alerta_js = '';
$codigo_valido = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $codigo = trim($_POST['codigo'] ?? '');

  if (!empty($codigo)) {
    $stmt = $conn->prepare("
      SELECT usuario_id 
      FROM recuperaciones_contrasena 
      WHERE token = ? AND expira > NOW() AND usado = FALSE
    ");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
      $alerta_js = "<script>
        window.onload = () => {
          mostrarVentanaMensaje('¡Código correcto! Ahora puedes restablecer tu contraseña', false, () => {
            window.location.href = 'resetear.php?token=" . urlencode($codigo) . "';
          });
        };
      </script>";
    } else {
      $alerta_js = "<script>
        window.onload = () => {
          mostrarVentanaMensaje('Código inválido, usado o expirado', true);
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
  <title>Verificar código</title>
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

    .contenedor-verificacion {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: -40px;
    }

    .titulo-verificacion {
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

  <div class="contenedor-verificacion">
    <h1 class="titulo-verificacion">Verificar código</h1>

    <form method="POST" class="formulario-recuperar">
      <input type="text" name="codigo" placeholder="Ingresa tu código mágico" required>
      <button class="button-submit" type="submit">Validar código</button>
      <p style="text-align: center; margin-top: 10px;">
        <a href="formulario_recuperar_contrasena.php" style="color: #4F88FF; font-weight: bold;">
          ← Volver a recuperación
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

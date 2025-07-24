<?php
require 'consultas/reset.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Restablecer contraseña</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .body-form {
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url('../img/BG.png');
      background-size: cover;
      background-position: center;
      height: 100vh;
    }

    .formulario-reset {
      background-color: rgba(10, 59, 6, 0.6);
      padding: 40px 60px;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      width: 500px; /* ⬅️ Más ancho para evitar cortes */
      height: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.4);
    }

    .titulo-reset {
      font-size: 2.4em; /* ⬅️ Ajustado para que sea una sola línea */
      font-weight: bold;
      color: white;
      font-family: 'comic sans ms', sans-serif;
      text-align: center;
      margin-bottom: 20px;
      white-space: nowrap; /* ⬅️ Evita salto de línea */
    }

    input {
      width: 100%;
      padding: 12px;
      border: 2px solid #CCE0FF;
      border-radius: 6px;
      font-size: 1em;
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
      font-size: 1em;
    }

    .button-submit:hover {
      background-image: url('../img/btn hover.png');
      background-size: 200px 40px;
      padding: 9px;
      font-size: 18px;
      margin-bottom: -1px;
      margin-right: 2px;
    }

    .volver {
      text-align: center;
      font-size: 0.9em;
      margin-top: 10px;
    }

    .volver a {
      color: #4F88FF;
      font-weight: bold;
      text-decoration: none;
    }
  </style>
</head>
<body class="body-form">
  <?= $alerta_js ?>

  <?php if ($usuario_id && !$exito): ?>
  <form method="POST" class="formulario-reset">
    <h1 class="titulo-reset">Restablecer contraseña</h1>

    <input type="password" name="contrasena" placeholder="Nueva contraseña" required>
    <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
    <button class="button-submit" type="submit">Restablecer contraseña</button>

    <div class="volver">
      <a href="index.php">← Volver al inicio de sesión</a>
    </div>
  </form>
  <?php endif; ?>

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

    function cerrarModal() {
      document.getElementById("mensaje-modal").style.display = "none";
      <?php if ($exito): ?>
        window.location.href = "index.php";
      <?php endif; ?>
    }
  </script>
</body>
</html>

<?php
require 'consultas/loguear.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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

    .formulario-login {
      background-color: rgba(10, 59, 6, 0.6);
      padding: 60px;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      width: 450px;
      height: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.4);
    }

    .titulo-login {
      font-size: 2.8em;
      font-weight: bold;
      color: white;
      font-family: 'comic sans ms', sans-serif;
      margin-bottom: 20px;
      text-align: center;
    }

    input {
      padding: 12px;
      border: 2px solid #CCE0FF;
      border-radius: 6px;
      font-size: 1em;
    }

    .button-submit {
      background-image: url('../img/btn normal.png');
      background-size: 150px 40px;
      background-position: center;
      background-repeat: no-repeat;
      background-color: transparent;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      font-family: 'comic sans ms', sans-serif;
      font-size: 1em;
      margin-top: 8px;
    }

    .recuperar,
    .registro {
      font-size: 0.9em;
      text-align: center;
      color: white;
    }

    .recuperar a,
    .registro a {
      color: #4cd137;
      font-weight: bold;
      text-decoration: none;
    }
  </style>
</head>

<body class="body-form">
  <?php if ($mostrar_toast_logout): ?>
    <script>
      window.onload = () => {
        mostrarVentanaMensaje('Sesión cerrada correctamente', false);
      };
    </script>
  <?php endif; ?>

  <form method="POST" class="formulario-login">
    <h1 class="titulo-login">Iniciar sesión</h1>

    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>

    <div class="recuperar">
      <a href="formulario_recuperar_contrasena.php">¿Se te olvidó la contraseña?</a>
    </div>

    <button class="button-submit" type="submit">Ingresar</button>

    <div class="registro">
      ¿Para crearte una cuenta? <a href="formulario_registro.php">Presiona aquí</a>
    </div>
  </form>

  <script src="js/toast.js"></script>
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

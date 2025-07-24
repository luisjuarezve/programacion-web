<?php
require 'consultas/loguear.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="css/styles.css">
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
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <!-- 🔑 Enlace para recuperación de contraseña -->
    <p style="text-align: right; margin: 5px 0 5px 0;">
      <a href="formulario_recuperar_contrasena.php" style="color: #4cd137; font-weight: bold; font-size: 0.9em;">
        ¿Se te olvido la contraseña?
      </a>
    </p>
    <button class="button-submit" type="submit">Ingresar</button>
    <!-- 👤 Enlace de registro justo debajo del botón -->
    <p style="text-align: center; margin-top: 5px; color: #fff;">
      ¿Para crearte una cuenta? 
      <a href="formulario_registro.php" style="color: #4cd137; font-weight: bold;">
        Presiona aquí
      </a>
    </p>
  </form>

  <script src="js/toast.js"></script>
  <script>
    function mostrarVentanaMensaje(mensaje, esError = false, callback = null) {
      const overlay = document.createElement('div');
      overlay.className = 'popup-overlay popup-abrir';
      overlay.innerHTML = `
        <div class=\"popup-content\" style=\"max-width:340px;padding:32px 24px;\">
          <div style=\"font-size:1.25em;font-weight:bold;margin-bottom:12px;${esError ? 'color:#d32f2f;' : 'color:#333;'}\">
            ${esError ? 'Error' : 'Mensaje'}
          </div>
          <div style=\"font-size:1.1em;margin-bottom:18px;\">${mensaje}</div>
          <button class=\"btn-cerrar-popup\" style=\"background:#1976d2;color:#fff;border:none;border-radius:8px;padding:8px 24px;font-size:1em;cursor:pointer;\">Aceptar</button>
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

<?php
require 'bdd/conexion.php';

$estado_token = '';
$token_usuario = '';
$alerta_js = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $token_usuario = trim($_POST['token'] ?? '');

  if (!empty($token_usuario)) {
    $stmt = $conn->prepare("SELECT token_expira FROM usuarios WHERE token_recuperacion = ?");
    $stmt->bind_param("s", $token_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
      $fila = $resultado->fetch_assoc();
      $expira = strtotime($fila['token_expira']);

      if ($expira > time()) {
        // Token válido
        $alerta_js = "<script>
          window.onload = () => {
            mostrarModal('Token válido. Redirigiendo...', 'resetear.php?token=$token_usuario');
          };
        </script>";
      } else {
        // Token expirado
        $alerta_js = "<script>
          window.onload = () => {
            mostrarModal('Este token ha expirado');
          };
        </script>";
      }
    } else {
      // Token inexistente
      $alerta_js = "<script>
        window.onload = () => {
          mostrarModal('Este token no existe');
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
  <title>Verificar token</title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
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
      from { opacity: 0; transform: scale(0.9); }
      to   { opacity: 1; transform: scale(1); }
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

  <form method="POST" class="formulario-login">
    <img src="img/Recupera.png" alt="Verificar" style="width: 100%; max-width: 380px; display: block; margin: 0 auto 10px;">
    <input type="text" name="token" placeholder="Pega aquí tu token" required>
    <button class="button-submit" type="submit">Validar Token</button>

    <p style="text-align: center; margin-top: 10px;">
      <a href="index.php" style="color: #4F88FF; font-weight: bold;">
        ← Volver al inicio de sesión
      </a>
    </p>
  </form>

  <script>
    function mostrarModal(texto, redireccion = null) {
      document.getElementById("mensaje-texto").innerText = texto;
      document.getElementById("mensaje-modal").style.display = "flex";
      if (redireccion) {
        document.querySelector("#mensaje-modal button").onclick = () => {
          window.location.href = redireccion;
        };
      }
    }

    function cerrarModal() {
      document.getElementById("mensaje-modal").style.display = "none";
    }
  </script>
</body>
</html>


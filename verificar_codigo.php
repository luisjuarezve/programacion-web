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
          mostrarModal('¡Código correcto! Ahora puedes restablecer tu contraseña');
        };
      </script>";
      $codigo_valido = true;
    } else {
      $alerta_js = "<script>
        window.onload = () => {
          mostrarModal('Código inválido, usado o expirado');
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
    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    .modal-contenido {
      background: #fff;
      padding: 30px 24px 18px 24px;
      border-radius: 14px;
      text-align: center;
      box-shadow: 0 4px 24px #0002;
      min-width: 280px;
      animation: aparecer 0.3s ease;
    }
    .modal-contenido p {
      font-size: 1.15em;
      margin-bottom: 18px;
      color: #333;
    }
    .modal-contenido button {
      background: #4F88FF;
      color: #fff;
      border: none;
      padding: 8px 22px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 1em;
      cursor: pointer;
      transition: background 0.2s;
    }
    .modal-contenido button:hover {
      background: #3466c2;
    }
    @keyframes aparecer {
      from { opacity: 0; transform: scale(0.95);}
      to { opacity: 1; transform: scale(1);}
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
    <input type="text" name="codigo" placeholder="Ingresa tu código mágico" required>
    <button class="button-submit" type="submit">Validar código</button>
    <p style="text-align: center; margin-top: 10px;">
      <a href="recuperar_contrasena.php" style="color: #4F88FF; font-weight: bold;">
        ← Volver a recuperación
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
      <?php if ($codigo_valido && !empty($codigo)): ?>
        window.location.href = "restablecer_contrasena.php?token=<?= urlencode($codigo) ?>";
      <?php endif; ?>
    }
  </script>
</body>
</html>

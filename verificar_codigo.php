<?php
require 'bdd/conexion.php';
$alerta_js = '';

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
      header("Location: restablecer_contrasena.php?token=" . urlencode($codigo));
      exit;
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
    }
  </script>
</body>
</html>

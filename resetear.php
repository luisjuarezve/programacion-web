<?php
require 'bdd/conexion.php';
$alerta_js = '';
$exito = false;
$token = $_GET['token'] ?? '';
$usuario_id = null;

// Validar token
if (!empty($token)) {
    $stmt = $conn->prepare("
        SELECT usuario_id 
        FROM recuperaciones_contrasena 
        WHERE token = ? AND expira > NOW() AND usado = FALSE
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario_id = $resultado->fetch_assoc()['usuario_id'];
    } else {
        $alerta_js = "<script>
            window.onload = () => {
                mostrarModal('El código es inválido, usado o expirado');
            };
        </script>";
    }
} else {
    $alerta_js = "<script>
        window.onload = () => {
            mostrarModal('No se recibió ningún token');
        };
    </script>";
}

// Procesar cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_id) {
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    if (strlen($contrasena) < 6) {
        $alerta_js = "<script>
            window.onload = () => {
                mostrarModal('La contraseña debe tener al menos 6 caracteres');
            };
        </script>";
    } elseif ($contrasena !== $confirmar) {
        $alerta_js = "<script>
            window.onload = () => {
                mostrarModal('Las contraseñas no coinciden');
            };
        </script>";
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $usuario_id);
        if ($stmt->execute()) {
            // Marcar el token como usado
            $stmt = $conn->prepare("UPDATE recuperaciones_contrasena SET usado = TRUE WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $alerta_js = "<script>
                window.onload = () => {
                    mostrarModal('¡Contraseña actualizada correctamente!');
                };
            </script>";
            $exito = true;
        } else {
            $alerta_js = "<script>
                window.onload = () => {
                    mostrarModal('Hubo un error al actualizar la contraseña');
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
  <title>Restablecer contraseña</title>
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
  <?php if ($usuario_id && !$exito): ?>
  <form method="POST" class="formulario-recuperar">
    <input type="password" name="contrasena" placeholder="Nueva contraseña" required>
    <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>
    <button class="button-submit" type="submit">Restablecer contraseña</button>
    <p style="text-align: center; margin-top: 10px;">
      <a href="index.php" style="color: #4F88FF; font-weight: bold;">
        ← Volver al inicio de sesión
      </a>
    </p>
  </form>
  <?php endif; ?>
  <script>
    function mostrarModal(texto) {
      document.getElementById("mensaje-texto").innerText = texto;
      document.getElementById("mensaje-modal").style.display = "flex";
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

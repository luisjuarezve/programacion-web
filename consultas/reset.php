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
                mostrarVentanaMensaje('La contraseña debe tener al menos 6 caracteres', true);
            };
        </script>";
    } elseif ($contrasena !== $confirmar) {
        $alerta_js = "<script>
            window.onload = () => {
                mostrarVentanaMensaje('Las contraseñas no coinciden', true);
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
                    mostrarVentanaMensaje('¡Contraseña actualizada correctamente!', false, () => {
                        window.location.href = 'index.php';
                    });
                };
            </script>";
            $exito = true;
        } else {
            $alerta_js = "<script>
                window.onload = () => {
                    mostrarVentanaMensaje('Hubo un error al actualizar la contraseña', true);
                };
            </script>";
        }
    }
}
?>
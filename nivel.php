<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
  header("Location: login.php");
  exit;
}
$nivel = isset($_GET['n']) ? intval($_GET['n']) : 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ejercicios - Nivel <?= $nivel ?></title>
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .ejercicio-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .ejercicio {
      background: #f0f0f0;
      padding: 20px;
      width: 180px;
      text-align: center;
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.3s;
    }
    .ejercicio:hover {
      transform: scale(1.05);
    }
    .ejercicio.correcto {
      background-color: #c8e6c9;
      pointer-events: none;
    }
    .ejercicio.incorrecto {
      background-color: #ffcdd2;
    }
    .ejercicio-activo {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      z-index: 1000;
    }
    .overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 999;
    }
    .boton-reiniciar {
      margin-top: 20px;
      padding: 10px 20px;
      background: #2196F3;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h2 style="text-align: center;">Nivel <?= $nivel ?> - Resta de 3 dígitos</h2>
  <div class="ejercicio-grid" id="contenedor-ejercicios"></div>
  <div style="text-align: center;">
    <button class="boton-reiniciar" onclick="reiniciarNivel()">🔄 Reiniciar</button>
  </div>

  <script>
    const nivel = <?= $nivel ?>;
    const usuarioId = <?= $_SESSION['usuario_id'] ?>;
  </script>
  <script src="js/nivel.js"></script>
</body>
</html>

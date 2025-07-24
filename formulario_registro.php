<?php
require 'consultas/registro.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrarse</title>
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

    .formulario-registro {
      background-color: rgba(10, 59, 6, 0.6);
      padding: 40px 60px 40px 60px; /* ⬅️ Espacio vertical equilibrado */
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      width: 460px;
      height: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.4);
    }

    .titulo-registro {
      font-size: 2.8em;
      font-weight: bold;
      color: white;
      font-family: 'comic sans ms', sans-serif;
      margin-bottom: 20px;
      text-align: center;
    }

    input {
      width: 100%;
      padding: 12px;
      border: 2px solid #CCE0FF;
      border-radius: 6px;
      font-size: 1em;
    }

    .center {
      margin-top: 15px;
      width: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
    }

    .btn-small {
      width: 150px;
      height: 40px;
      font-size: 18px;
      background-size: 150px 40px;
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
    }

    .button-regresar {
      background-image: url('../img/btn cancel.png');
      background-size: 150px 40px;
      background-position: center;
      background-repeat: no-repeat;
      background-color: transparent;
      color: white;
      border: none;
      padding: 10px;
      text-align: center;
      text-decoration: none;
      font-family: 'comic sans ms', sans-serif;
    }

    .button-regresar:hover {
      background-image: url('../img/btn exit.png');
      padding: 8px;
    }
  </style>
</head>
<body class="body-form">
  <?php if (!empty($mensaje)) echo $mensaje; ?>

  <form method="POST" class="formulario-registro">
    <h1 class="titulo-registro">Regístrate</h1>

    <input type="text" name="nombre" placeholder="Nombre completo" value="<?php echo htmlspecialchars($nombre); ?>" required>
    <input type="email" name="email" placeholder="Correo electrónico" value="<?php echo htmlspecialchars($email); ?>" required>
    <input type="password" name="contrasena" placeholder="Contraseña" required>
    <input type="password" name="confirmar" placeholder="Confirmar contraseña" required>

    <div class="center">
      <button class="button-submit btn-small" type="submit">Registrarse</button>
      <a href="index.php" class="button-regresar btn-small">Volver</a>
    </div>
  </form>

  <script src="js/toast.js"></script>
</body>
</html>

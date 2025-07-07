<?php
$host = "localhost";
$usuario = "root"; // cambia esto si usas otro usuario
$contrasena = "";  // pon tu contraseña si la tienes
$base_datos = "plataforma_educativa";

$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>

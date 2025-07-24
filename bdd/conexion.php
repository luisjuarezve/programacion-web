<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "plataforma_educativa"; // Cambia esto si usas otro usuario o base de datos
// $usuario = "pwgrupo8_estudiante"; // cambia esto si usas otro usuario
// $contrasena = "Pwgrupo8-8";  // pon tu contraseña si la tienes
// $base_datos = "pwgrupo8_plataforma_educativa";

$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>

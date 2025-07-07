<?php
session_start();
session_destroy();

// Guardar estado para mostrar mensaje en el login
session_start();
$_SESSION['logout_success'] = true;

header("Location: index.php");
exit;
?>

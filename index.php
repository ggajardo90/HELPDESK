<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: views/auth/login.php");
    exit;
}
?>

<h2>Bienvenido <?= $_SESSION["usuario_nombre"] ?></h2>

<a href="views/solicitudes/listar.php">Ver Solicitudes</a> |
<a href="controllers/logout.php">Cerrar sesión</a>
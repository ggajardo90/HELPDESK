<?php
require_once "../config/database.php";

if (!isset($_GET["id"]) || !isset($_GET["sol"])) {
    die("Error en la solicitud");
}

$idNoti = $_GET["id"];
$idSol = $_GET["sol"];

// marcar como leída
$conexion->prepare("UPDATE notificaciones SET leido = 1 WHERE id = ?")
    ->execute([$idNoti]);

// redirigir
header("Location: ../views/solicitudes/ver.php?id=" . $idSol);
exit;

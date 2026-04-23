<?php
require_once "../config/database.php";

$conexion->query("UPDATE notificaciones SET leido = 1 WHERE leido = 0");

header("Location: ../views/dashboard.php");
exit;
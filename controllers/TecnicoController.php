<?php
session_start();
require_once "../config/database.php";

$id_solicitud = $_POST["id_solicitud"];
$id_tecnico = $_POST["id_tecnico"];
$id_usuario = $_SESSION["usuario_id"];

// actualizar técnico
$sql = "UPDATE solicitudes SET id_tecnico = :tecnico WHERE id = :id";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ":tecnico" => $id_tecnico,
    ":id" => $id_solicitud
]);

// historial
$sqlHist = "INSERT INTO historial (id_solicitud, accion, id_usuario)
            VALUES (:id_solicitud, :accion, :id_usuario)";

$accion = "Asignó técnico";

$stmt = $conexion->prepare($sqlHist);
$stmt->execute([
    ":id_solicitud" => $id_solicitud,
    ":accion" => $accion,
    ":id_usuario" => $id_usuario
]);

header("Location: ../views/solicitudes/ver.php?id=" . $id_solicitud);
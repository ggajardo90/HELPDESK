<?php
session_start();
require_once "../config/database.php";

$id_solicitud = $_POST["id_solicitud"];
$id_estado = $_POST["id_estado"];
$id_usuario = $_SESSION["usuario_id"];

// actualizar estado
$sql = "UPDATE solicitudes SET id_estado = :estado WHERE id = :id";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    ":estado" => $id_estado,
    ":id" => $id_solicitud
]);

// guardar historial
$sqlHist = "INSERT INTO historial (id_solicitud, accion, id_usuario)
            VALUES (:id_solicitud, :accion, :id_usuario)";

$accion = "Cambio de estado";

$stmt = $conexion->prepare($sqlHist);
$stmt->execute([
    ":id_solicitud" => $id_solicitud,
    ":accion" => $accion,
    ":id_usuario" => $id_usuario
]);

header("Location: ../views/solicitudes/ver.php?id=" . $id_solicitud);
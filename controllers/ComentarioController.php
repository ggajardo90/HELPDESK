<?php
session_start();
require_once "../config/database.php";

$id_solicitud = $_POST["id_solicitud"];
$comentario = $_POST["comentario"];
$id_usuario = $_SESSION["usuario_id"];

// guardar comentario
$sql = "INSERT INTO comentarios (id_solicitud, id_usuario, comentario)
        VALUES (:id_solicitud, :id_usuario, :comentario)";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    ":id_solicitud" => $id_solicitud,
    ":id_usuario" => $id_usuario,
    ":comentario" => $comentario
]);

// historial
$sqlHist = "INSERT INTO historial (id_solicitud, accion, id_usuario)
            VALUES (:id_solicitud, :accion, :id_usuario)";

$accion = "Agregó comentario";

$stmt = $conexion->prepare($sqlHist);
$stmt->execute([
    ":id_solicitud" => $id_solicitud,
    ":accion" => $accion,
    ":id_usuario" => $id_usuario
]);

header("Location: ../views/solicitudes/ver.php?id=" . $id_solicitud);

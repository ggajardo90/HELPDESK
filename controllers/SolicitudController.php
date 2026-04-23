<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $id_usuario = $_SESSION["usuario_id"];
    $id_prioridad = $_POST["id_prioridad"];

    // INSERT SOLICITUD
    $sql = "INSERT INTO solicitudes (titulo, descripcion, id_usuario, id_prioridad, id_estado) 
            VALUES (:titulo, :descripcion, :usuario, :prioridad, 1)";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ":titulo" => $titulo,
        ":descripcion" => $descripcion,
        ":usuario" => $id_usuario,
        ":prioridad" => $id_prioridad
    ]);

    // 🔥 OBTENER ID DE LA SOLICITUD
    $idSolicitud = $conexion->lastInsertId();

    // 🔔 CREAR NOTIFICACIÓN
    $mensaje = "Nueva solicitud: " . $titulo;

    $sqlNoti = "INSERT INTO notificaciones (mensaje, id_solicitud) 
                VALUES (:mensaje, :id_solicitud)";

    $stmtNoti = $conexion->prepare($sqlNoti);
    $stmtNoti->execute([
        ":mensaje" => $mensaje,
        ":id_solicitud" => $idSolicitud
    ]);

    header("Location: ../views/solicitudes/listar.php");
}
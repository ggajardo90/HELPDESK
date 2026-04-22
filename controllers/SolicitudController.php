<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $id_prioridad = $_POST["id_prioridad"];
    $id_usuario = $_SESSION["usuario_id"];

    $sql = "INSERT INTO solicitudes (titulo, descripcion, id_usuario, id_prioridad)
            VALUES (:titulo, :descripcion, :id_usuario, :id_prioridad)";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(":titulo", $titulo);
    $stmt->bindParam(":descripcion", $descripcion);
    $stmt->bindParam(":id_usuario", $id_usuario);
    $stmt->bindParam(":id_prioridad", $id_prioridad);

    if ($stmt->execute()) {
        header("Location: ../views/solicitudes/listar.php");
    } else {
        echo "Error al guardar";
    }
}

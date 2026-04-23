<?php
session_start();
require_once "../config/database.php";

// SOLO ADMIN
if ($_SESSION["usuario_rol"] != 1) {
    die("Acceso no permitido");
}

$nombre = $_POST["nombre"];
$email = $_POST["email"];
$password = $_POST["password"];
$id_rol = $_POST["id_rol"];

// 🔐 ENCRIPTAR PASSWORD (MUY IMPORTANTE)
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// guardar
$sql = "INSERT INTO usuarios (nombre, email, password, id_rol)
        VALUES (:nombre, :email, :password, :rol)";

$stmt = $conexion->prepare($sql);
$stmt->execute([
    ":nombre" => $nombre,
    ":email" => $email,
    ":password" => $passwordHash,
    ":rol" => $id_rol
]);

header("Location: ../views/dashboard.php");

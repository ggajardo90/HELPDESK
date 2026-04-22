<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: auth/login.php");
    exit;
}

// métricas
$total = $conexion->query("SELECT COUNT(*) FROM solicitudes")->fetchColumn();
$pendientes = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_estado = 1")->fetchColumn();
$proceso = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_estado = 2")->fetchColumn();
$finalizadas = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_estado = 3")->fetchColumn();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="container mt-5">

    <h2>Dashboard</h2>

    <div class="row">

        <div class="col-md-3">
            <div class="card bg-dark text-white text-center p-3">
                <h5>Total</h5>
                <h2><?= $total ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white text-center p-3">
                <h5>Pendientes</h5>
                <h2><?= $pendientes ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-primary text-white text-center p-3">
                <h5>En proceso</h5>
                <h2><?= $proceso ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white text-center p-3">
                <h5>Finalizadas</h5>
                <h2><?= $finalizadas ?></h2>
            </div>
        </div>

    </div>

    <br>

    <a href="solicitudes/listar.php" class="btn btn-primary">Ver Solicitudes</a>
    <a href="../controllers/logout.php" class="btn btn-danger">Cerrar sesión</a>

</body>

</html>
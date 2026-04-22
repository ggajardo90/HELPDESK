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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar p-3">
            <h4 class="text-white">HelpDesk</h4>

            <a href="#" class="active">Dashboard</a>
            <a href="solicitudes/listar.php">Solicitudes</a>

            <hr>
            <a href="../controllers/logout.php">Cerrar sesión</a>
        </div>

        <!-- CONTENIDO -->
        <div class="content p-4 w-100">

            <h3>Dashboard</h3>

            <!-- CARDS -->
            <div class="row mt-4">

                <div class="col-md-3">
                    <div class="card-box bg-primary">
                        <h6>Total</h6>
                        <h2><?= $total ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card-box bg-warning">
                        <h6>Pendientes</h6>
                        <h2><?= $pendientes ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card-box bg-info">
                        <h6>En proceso</h6>
                        <h2><?= $proceso ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card-box bg-success">
                        <h6>Finalizadas</h6>
                        <h2><?= $finalizadas ?></h2>
                    </div>
                </div>

            </div>

            <!-- GRAFICOS -->

            <div class="row mt-4">

                <!-- GRAFICO ESTADO -->
                <div class="col-md-6">
                    <div class="card dashboard-card">
                        <div class="card-header d-flex justify-content-between">
                            <span>Solicitudes por Estado</span>
                            <small>Este mes</small>
                        </div>

                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="graficoEstado"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GRAFICO PRIORIDAD -->
                <div class="col-md-6">
                    <div class="card dashboard-card">
                        <div class="card-header d-flex justify-content-between">
                            <span>Solicitudes por Prioridad</span>
                            <small>Este mes</small>
                        </div>

                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="graficoPrioridad"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        // gráfico estado
        new Chart(document.getElementById("graficoEstado"), {
            type: "doughnut",
            data: {
                labels: ["Pendiente", "En proceso", "Finalizado"],
                datasets: [{
                    data: [<?= $pendientes ?>, <?= $proceso ?>, <?= $finalizadas ?>],
                    backgroundColor: ["#ffc107", "#17a2b8", "#28a745"]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 🔥 CLAVE
                cutout: "60%" // hace el donut más limpio
            }
        });

        // gráfico prioridad
        <?php
        $alta = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_prioridad = 3")->fetchColumn();
        $media = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_prioridad = 2")->fetchColumn();
        $baja = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_prioridad = 1")->fetchColumn();
        ?>

        new Chart(document.getElementById("graficoPrioridad"), {
            type: "bar",
            data: {
                labels: ["Alta", "Media", "Baja"],
                datasets: [{
                    data: [<?= $alta ?>, <?= $media ?>, <?= $baja ?>],
                    backgroundColor: ["#dc3545", "#ffc107", "#28a745"]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 🔥 CLAVE
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>

</body>



</html>
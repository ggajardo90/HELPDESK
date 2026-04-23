<?php
session_start();
require_once "../config/database.php";

// VALIDAR SESIÓN
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$rol = $_SESSION["usuario_rol"];

// MÉTRICAS
$total = $conexion->query("SELECT COUNT(*) FROM solicitudes")->fetchColumn();
$pendientes = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_estado = 1")->fetchColumn();
$proceso = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_estado = 2")->fetchColumn();
$finalizadas = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_estado = 3")->fetchColumn();

// PRIORIDADES
$alta = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_prioridad = 3")->fetchColumn();
$media = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_prioridad = 2")->fetchColumn();
$baja = $conexion->query("SELECT COUNT(*) FROM solicitudes WHERE id_prioridad = 1")->fetchColumn();

// ÚLTIMAS SOLICITUDES (3)
$ultimas = $conexion->query("SELECT * FROM solicitudes ORDER BY id DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h3>Dashboard</h3>

<!-- CARDS -->
<div class="row mt-4">

    <div class="col-md-3">
        <div class="card-box bg-blue">
            <h6>Total</h6>
            <h2><?= $total ?></h2>
            <i class="fas fa-database"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box bg-yellow">
            <h6>Pendientes</h6>
            <h2><?= $pendientes ?></h2>
            <i class="fas fa-clock"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box bg-cyan">
            <h6>En proceso</h6>
            <h2><?= $proceso ?></h2>
            <i class="fas fa-tools"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box bg-green">
            <h6>Finalizadas</h6>
            <h2><?= $finalizadas ?></h2>
            <i class="fas fa-check"></i>
        </div>
    </div>

</div>

<!-- 🔒 GRÁFICOS SOLO ADMIN -->
<?php if ($rol == 1): ?>
    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card p-3">
                <h5>Solicitudes por Estado</h5>
                <div class="chart-container">
                    <canvas id="graficoEstado"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5>Solicitudes por Prioridad</h5>
                <div class="chart-container">
                    <canvas id="graficoPrioridad"></canvas>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<!-- TABLA -->
<div class="card mt-4 p-3">
    <h5>Últimas solicitudes</h5>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($ultimas as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= $u['titulo'] ?></td>
                    <td><?= $u['fecha_creacion'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- 🔒 SCRIPTS SOLO ADMIN -->
<?php if ($rol == 1): ?>
    <script>
        new Chart(document.getElementById("graficoEstado"), {
            type: "doughnut",
            data: {
                labels: ["Pendiente", "Proceso", "Finalizado"],
                datasets: [{
                    data: [<?= $pendientes ?>, <?= $proceso ?>, <?= $finalizadas ?>],
                    backgroundColor: ["#f6b93b", "#38ada9", "#78e08f"]
                }]
            },
            options: {
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById("graficoPrioridad"), {
            type: "bar",
            data: {
                labels: ["Alta", "Media", "Baja"],
                datasets: [{
                    data: [<?= $alta ?>, <?= $media ?>, <?= $baja ?>],
                    backgroundColor: ["#e55039", "#f6b93b", "#78e08f"]
                }]
            },
            options: {
                maintainAspectRatio: false
            }
        });
    </script>
<?php endif; ?>

<?php
$contenido = ob_get_clean();
include "layouts/main.php";
?>
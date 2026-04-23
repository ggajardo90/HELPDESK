<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$sql = "SELECT s.*, p.nombre AS prioridad, e.nombre AS estado
        FROM solicitudes s
        LEFT JOIN prioridades p ON s.id_prioridad = p.id
        LEFT JOIN estados e ON s.id_estado = e.id
        ORDER BY s.id DESC";

$solicitudes = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h3>Solicitudes</h3>

<div class="card p-3 mt-3">

    <!-- TOP BAR -->
    <div class="d-flex justify-content-between mb-3">

        <a href="crear.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Nueva Solicitud
        </a>

        <input type="text" id="buscador" class="form-control w-25" placeholder="Buscar...">

    </div>

    <!-- TABLA -->
    <table class="table table-hover table-bordered">

        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody id="tablaSolicitudes">

            <?php foreach ($solicitudes as $s): ?>
                <tr>

                    <td><?= $s['id'] ?></td>

                    <td>
                        <a>
                            <?= $s['titulo'] ?>
                        </a>
                    </td>

                    <!-- PRIORIDAD -->
                    <td>
                        <?php
                        $colorP = "secondary";
                        if ($s['prioridad'] == "Alta") $colorP = "danger";
                        if ($s['prioridad'] == "Media") $colorP = "warning";
                        if ($s['prioridad'] == "Baja") $colorP = "success";
                        ?>
                        <span class="badge badge-<?= $colorP ?>">
                            <?= $s['prioridad'] ?>
                        </span>
                    </td>

                    <!-- ESTADO -->
                    <td>
                        <?php
                        $colorE = "secondary";
                        if ($s['estado'] == "Pendiente") $colorE = "warning";
                        if ($s['estado'] == "En proceso") $colorE = "info";
                        if ($s['estado'] == "Finalizado") $colorE = "success";
                        ?>
                        <span class="badge badge-<?= $colorE ?>">
                            <?= $s['estado'] ?>
                        </span>
                    </td>

                    <td><?= $s['fecha_creacion'] ?></td>

                    <!-- ACCIONES -->
                    <td>
                        <a href="ver.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="editar.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>

                </tr>
            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<!-- BUSCADOR JS -->
<script>
    document.getElementById("buscador").addEventListener("keyup", function() {
        let filtro = this.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaSolicitudes tr");

        filas.forEach(fila => {
            let texto = fila.textContent.toLowerCase();
            fila.style.display = texto.includes(filtro) ? "" : "none";
        });
    });
</script>

<?php
$contenido = ob_get_clean();
include "../layouts/main.php";
?>
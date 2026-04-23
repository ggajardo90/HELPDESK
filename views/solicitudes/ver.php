<?php
session_start();
require_once "../../config/database.php";

// Validar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$rol = $_SESSION["usuario_rol"];
$id = $_GET["id"];

// técnicos
$tecnicos = $conexion->query("SELECT * FROM usuarios WHERE id_rol = 2")->fetchAll(PDO::FETCH_ASSOC);

// solicitud
$sql = "SELECT s.*, p.nombre AS prioridad, e.nombre AS estado, 
               u.nombre as usuario, 
               t.nombre as tecnico
        FROM solicitudes s
        LEFT JOIN prioridades p ON s.id_prioridad = p.id
        LEFT JOIN estados e ON s.id_estado = e.id
        LEFT JOIN usuarios u ON s.id_usuario = u.id
        LEFT JOIN usuarios t ON s.id_tecnico = t.id
        WHERE s.id = :id";

$stmt = $conexion->prepare($sql);
$stmt->execute([":id" => $id]);
$solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

// comentarios
$sqlComentarios = "SELECT c.*, u.nombre 
                   FROM comentarios c
                   JOIN usuarios u ON c.id_usuario = u.id
                   WHERE id_solicitud = :id
                   ORDER BY c.id DESC";

$stmt = $conexion->prepare($sqlComentarios);
$stmt->execute([":id" => $id]);
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// estados
$estados = $conexion->query("SELECT * FROM estados")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h3>Detalle de Solicitud</h3>

<div class="row mt-4">

    <!-- INFO -->
    <div class="col-md-4">
        <div class="card p-3">

            <h5><?= $solicitud["titulo"] ?></h5>
            <p class="text-muted"><?= $solicitud["descripcion"] ?></p>

            <hr>

            <p><b>Solicitante:</b> <?= $solicitud["usuario"] ?></p>
            <p><b>Fecha:</b> <?= $solicitud["fecha_creacion"] ?></p>
            <?php if (!$solicitud["tecnico"]): ?>
                <div class="alert alert-warning">
                    ⚠ Esta solicitud no tiene técnico asignado
                </div>
            <?php endif; ?>

            <!-- PRIORIDAD -->
            <?php
            $colorP = "secondary";
            if ($solicitud['prioridad'] == "Alta") $colorP = "danger";
            if ($solicitud['prioridad'] == "Media") $colorP = "warning";
            if ($solicitud['prioridad'] == "Baja") $colorP = "success";
            ?>

            <p>
                <b>Prioridad:</b>
                <span class="badge badge-<?= $colorP ?>">
                    <?= $solicitud["prioridad"] ?>
                </span>
            </p>

            <!-- ESTADO -->
            <?php
            $colorE = "secondary";
            if ($solicitud['estado'] == "Pendiente") $colorE = "warning";
            if ($solicitud['estado'] == "En proceso") $colorE = "info";
            if ($solicitud['estado'] == "Finalizado") $colorE = "success";
            ?>

            <p>
                <b>Estado:</b>
                <span class="badge badge-<?= $colorE ?>">
                    <?= $solicitud["estado"] ?>
                </span>
            </p>

            <hr>

            <!-- CAMBIAR ESTADO (ADMIN + TECNICO) -->
            <?php if ($rol == 1 || $rol == 2): ?>
                <form action="../../controllers/EstadoController.php" method="POST">

                    <input type="hidden" name="id_solicitud" value="<?= $id ?>">

                    <label>Cambiar estado</label>

                    <select name="id_estado" class="form-control">
                        <?php foreach ($estados as $e): ?>
                            <option value="<?= $e['id'] ?>">
                                <?= $e['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button class="btn btn-warning btn-block mt-2">
                        Actualizar
                    </button>

                </form>
            <?php endif; ?>

            <hr>

            <!-- ASIGNAR TECNICO (SOLO ADMIN) -->
            <?php if ($rol == 1): ?>
                <form action="../../controllers/TecnicoController.php" method="POST">

                    <input type="hidden" name="id_solicitud" value="<?= $id ?>">

                    <label>Asignar técnico</label>

                    <select name="id_tecnico" class="form-control">
                        <option value="">-- Seleccionar --</option>

                        <?php foreach ($tecnicos as $t): ?>
                            <option value="<?= $t['id'] ?>">
                                <?= $t['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button class="btn btn-info btn-block mt-2">
                        Asignar
                    </button>

                </form>
            <?php endif; ?>

        </div>
    </div>

    <!-- COMENTARIOS -->
    <div class="col-md-8">

        <div class="card p-3">

            <h5>Comentarios</h5>

            <!-- FORM -->
            <form action="../../controllers/ComentarioController.php" method="POST">
                <input type="hidden" name="id_solicitud" value="<?= $id ?>">

                <textarea name="comentario" class="form-control" rows="3" style="resize: none;" required></textarea>

                <button class="btn btn-primary mt-2">
                    <i class="fas fa-paper-plane"></i> Enviar
                </button>
            </form>

            <hr>

            <!-- LISTA -->
            <div class="comentarios-box">

                <?php foreach ($comentarios as $c): ?>
                    <div class="comentario-item">

                        <div class="comentario-header">
                            <b><?= $c["nombre"] ?></b>
                            <small><?= $c["fecha"] ?></small>
                        </div>

                        <div class="comentario-body">
                            <?= $c["comentario"] ?>
                        </div>

                    </div>
                <?php endforeach; ?>

            </div>

        </div>

    </div>

</div>

<?php
$contenido = ob_get_clean();
include "../layouts/main.php";
?>
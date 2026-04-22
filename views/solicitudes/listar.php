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
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitudes</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>

<body class="container mt-5">

    <h3>Solicitudes</h3>

    <a href="crear.php" class="btn btn-primary mb-3">Nueva Solicitud</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($solicitudes as $s): ?>
                <tr>
                    <td><?= $s['id'] ?></td>
                    <td>
                        <a href="ver.php?id=<?= $s['id'] ?>">
                            <?= $s['titulo'] ?>
                        </a>
                    </td>
                    <td>
                        <?php
                        $color = "secondary";

                        if ($s['prioridad'] == "Baja") $color = "success";
                        if ($s['prioridad'] == "Media") $color = "warning";
                        if ($s['prioridad'] == "Alta") $color = "danger";
                        ?>
                        <span class="badge badge-<?= $color ?>">
                            <?= $s['prioridad'] ?>
                        </span>
                    </td>
                    <td>
                        <?php
                        $color = "secondary";

                        if ($s['estado'] == "Pendiente") $color = "warning";
                        if ($s['estado'] == "En proceso") $color = "primary";
                        if ($s['estado'] == "Finalizado") $color = "success";
                        ?>
                        <span class="badge badge-<?= $color ?>">
                            <?= $s['estado'] ?>
                        </span>
                    </td>
                    <td><?= $s['fecha_creacion'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>
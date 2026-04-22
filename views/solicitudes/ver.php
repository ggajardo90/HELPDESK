<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET["id"];

// solicitud
$sql = "SELECT s.*, p.nombre AS prioridad, e.nombre AS estado
        FROM solicitudes s
        LEFT JOIN prioridades p ON s.id_prioridad = p.id
        LEFT JOIN estados e ON s.id_estado = e.id
        WHERE s.id = :id";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();
$solicitud = $stmt->fetch(PDO::FETCH_ASSOC);

// comentarios
$sqlComentarios = "SELECT c.*, u.nombre 
                   FROM comentarios c
                   JOIN usuarios u ON c.id_usuario = u.id
                   WHERE id_solicitud = :id
                   ORDER BY c.id DESC";

$stmt = $conexion->prepare($sqlComentarios);
$stmt->bindParam(":id", $id);
$stmt->execute();
$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// estados
$estados = $conexion->query("SELECT * FROM estados")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>

<body class="container mt-5">

    <h3><?= $solicitud["titulo"] ?></h3>

    <p><b>Descripción:</b> <?= $solicitud["descripcion"] ?></p>
    <p><b>Estado:</b> <?= $solicitud["estado"] ?></p>
    <p><b>Prioridad:</b> <?= $solicitud["prioridad"] ?></p>

    <hr>

    <h5>Cambiar estado</h5>

    <form action="../../controllers/EstadoController.php" method="POST">
        <input type="hidden" name="id_solicitud" value="<?= $id ?>">

        <select name="id_estado" class="form-control">
            <?php foreach ($estados as $e): ?>
                <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <button class="btn btn-warning mt-2">Actualizar</button>
    </form>

    <hr>

    <h5>Comentarios</h5>

    <form action="../../controllers/ComentarioController.php" method="POST">
        <input type="hidden" name="id_solicitud" value="<?= $id ?>">

        <textarea name="comentario" class="form-control" required></textarea>
        <button class="btn btn-primary mt-2">Comentar</button>
    </form>

    <br>

    <?php foreach ($comentarios as $c): ?>
        <div class="card mb-2">
            <div class="card-body">
                <b><?= $c["nombre"] ?></b><br>
                <?= $c["comentario"] ?><br>
                <small><?= $c["fecha"] ?></small>
            </div>
        </div>
    <?php endforeach; ?>

</body>

</html>
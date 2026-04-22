<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

// cargar prioridades
$prioridades = $conexion->query("SELECT * FROM prioridades")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nueva Solicitud</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>

<body class="container mt-5">

    <h3>Nueva Solicitud</h3>

    <form action="../../controllers/SolicitudController.php" method="POST">

        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label>Prioridad</label>
            <select name="id_prioridad" class="form-control" required>
                <?php foreach ($prioridades as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-success">Guardar</button>
    </form>

</body>

</html>
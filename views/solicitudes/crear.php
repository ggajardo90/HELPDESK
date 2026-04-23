<?php
session_start();
require_once "../../config/database.php";

// validar sesión
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

// SOLO ADMIN Y USUARIO (el técnico no crea)
if ($_SESSION["usuario_rol"] == 2) {
    die("Acceso no permitido");
}

// cargar prioridades
$prioridades = $conexion->query("SELECT * FROM prioridades")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <h3 class="mb-0">Nueva Solicitud</h3>

    <a href="listar.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

</div>

<!-- FORM -->
<div class="card p-4 shadow-sm">

    <form action="../../controllers/SolicitudController.php" method="POST">

        <div class="row">

            <!-- TITULO -->
            <div class="col-md-6">
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control" required>
                </div>
            </div>

            <!-- PRIORIDAD -->
            <div class="col-md-6">
                <div class="form-group">
                    <label>Prioridad</label>
                    <select name="id_prioridad" class="form-control">
                        <?php foreach ($prioridades as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= $p['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- DESCRIPCIÓN -->
            <div class="col-md-12">
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="5" style="resize: none;" required></textarea>
                </div>
            </div>

        </div>

        <!-- BOTONES -->
        <div class="text-right">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Crear Solicitud
            </button>
        </div>

    </form>

</div>

<?php
$contenido = ob_get_clean();
include "../layouts/main.php";
?>
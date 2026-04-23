<?php
session_start();
require_once "../../config/database.php";

// SOLO ADMIN
if ($_SESSION["usuario_rol"] != 1) {
    die("Acceso no permitido");
}

// obtener roles
$roles = $conexion->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h3>Crear Usuario</h3>

<div class="card p-3 mt-3">

    <form action="../../controllers/UsuarioController.php" method="POST">

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Correo</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Rol</label>
            <select name="id_rol" class="form-control">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= $r['id'] ?>">
                        <?= $r['nombre'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="btn btn-success mt-2">
            Crear Usuario
        </button>

    </form>

</div>

<?php
$contenido = ob_get_clean();
include "../layouts/main.php";
?>
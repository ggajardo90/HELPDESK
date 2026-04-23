<?php
session_start();
require_once "../../config/database.php";

// SOLO ADMIN
if ($_SESSION["usuario_rol"] != 1) {
    die("Acceso no permitido");
}

// obtener usuarios + rol
$sql = "SELECT u.*, r.nombre AS rol 
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id
        ORDER BY u.id DESC";

$usuarios = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    
    <h3 class="mb-0">Usuarios</h3>

    <a href="crear.php" class="btn btn-success">
        <i class="fas fa-user-plus"></i> Nuevo Usuario
    </a>

</div>

<!-- FILTRO -->
<div class="row mb-3">
    <div class="col-md-6">
        <input type="text" id="filtroUsuarios" class="form-control"
            placeholder="🔍 Buscar por nombre, correo o rol...">
    </div>
</div>

<!-- TABLA -->
<div class="card p-3">

    <table class="table table-hover" id="tablaUsuarios">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u["id"] ?></td>
                    <td><?= $u["nombre"] ?></td>
                    <td><?= $u["email"] ?></td>
                    
                    <td>
                        <?php
                        $color = "secondary";
                        if ($u["rol"] == "Admin") $color = "danger";
                        if ($u["rol"] == "Tecnico") $color = "info";
                        if ($u["rol"] == "Usuario") $color = "primary";
                        ?>
                        <span class="badge badge-<?= $color ?>">
                            <?= $u["rol"] ?>
                        </span>
                    </td>

                    <td class="text-center">
                        <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a href="../../controllers/EliminarUsuario.php?id=<?= $u['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Eliminar usuario?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<!-- SCRIPT FILTRO -->
<script>
document.getElementById("filtroUsuarios").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tablaUsuarios tbody tr");

    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();

        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});
</script>

<?php
$contenido = ob_get_clean();
include "../layouts/main.php";
?>
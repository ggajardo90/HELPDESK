<?php if (!isset($_SESSION)) session_start(); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>HelpDesk</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/helpdesk/assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <h4 class="logo">HelpDesk</h4>

            <a href="/helpdesk/views/dashboard.php">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>

            <a href="/helpdesk/views/solicitudes/listar.php">
                <i class="fas fa-ticket-alt"></i> Solicitudes
            </a>

            <a href="/helpdesk/views/usuarios/listar.php">
                <i class="fas fa-users"></i> Usuarios
            </a>

            <a href="/helpdesk/controllers/logout.php">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>

        <!-- CONTENIDO -->
        <div class="main-content">

            <!-- NAVBAR -->
            <div class="topbar d-flex justify-content-end align-items-center">

                <!-- 🔔 CAMPANA -->
                <?php
                if ($_SESSION["usuario_rol"] == 1) {
                    require_once __DIR__ . "/../../config/database.php";

                    $sqlNoti = "SELECT * FROM notificaciones WHERE leido = 0 ORDER BY id DESC";
                    $notificaciones = $conexion->query($sqlNoti)->fetchAll(PDO::FETCH_ASSOC);
                    $totalNoti = count($notificaciones);
                }
                ?>

                <?php if ($_SESSION["usuario_rol"] == 1): ?>
                    <div class="dropdown mr-3">

                        <button class="btn position-relative" data-toggle="dropdown">
                            <i class="fas fa-bell fa-lg"></i>

                            <?php if ($totalNoti > 0): ?>
                                <span class="badge badge-danger badge-pill"
                                    style="position:absolute; top:0; right:0;">
                                    <?= $totalNoti ?>
                                </span>
                            <?php endif; ?>
                        </button>

                        <!-- DROPDOWN -->
                        <div class="dropdown-menu dropdown-menu-right p-2" style="width:300px;">
                            <h6 class="dropdown-header">Notificaciones</h6>

                            <?php if ($totalNoti > 0): ?>
                                <?php foreach ($notificaciones as $n): ?>

                                    <?php if (!empty($n['id_solicitud'])): ?>
                                        <a href="/helpdesk/controllers/IrNotificacion.php?id=<?= $n['id'] ?>&sol=<?= $n['id_solicitud'] ?>"
                                            class="dropdown-item small">
                                            <?= $n["mensaje"] ?>
                                        </a>
                                    <?php else: ?>
                                        <div class="dropdown-item small text-muted">
                                            <?= $n["mensaje"] ?>
                                        </div>
                                    <?php endif; ?>

                                <?php endforeach; ?>

                                <div class="dropdown-divider"></div>

                                <a href="/helpdesk/controllers/LeerNotificaciones.php"
                                    class="dropdown-item text-center text-primary">
                                    Marcar todas como leídas
                                </a>
                            <?php else: ?>
                                <div class="dropdown-item text-muted">
                                    Sin notificaciones
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 👤 USUARIO -->
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <?= $_SESSION["usuario_nombre"] ?>
                </div>

            </div>

            <!-- CONTENIDO -->
            <div class="container-fluid mt-4">
                <?php echo $contenido; ?>
            </div>

        </div>

    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
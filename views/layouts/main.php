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

            <a href="/helpdesk/views/dashboard.php" class="active">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>

            <a href="/helpdesk/views/solicitudes/listar.php">
                <i class="fas fa-ticket-alt"></i> Solicitudes
            </a>

            <a href="/helpdesk/controllers/logout.php">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>

        <!-- CONTENIDO -->
        <div class="main-content">

            <!-- NAVBAR -->
            <div class="topbar">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <?= $_SESSION["usuario_nombre"] ?>
                </div>
            </div>

            <div class="container-fluid mt-4">
                <?php echo $contenido; ?>
            </div>

        </div>
    </div>

</body>

</html>
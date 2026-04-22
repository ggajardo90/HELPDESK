<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - HelpDesk</title>

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- CSS personalizado -->
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row vh-100">

            <!-- LADO IZQUIERDO -->
            <div class="col-md-6 d-none d-md-flex left-panel align-items-center justify-content-center">
                <div class="text-center">
                    <img src="../../assets/img/logo.png" class="logo">
                </div>
            </div>

            <!-- LADO DERECHO -->
            <div class="col-md-6 d-flex align-items-center justify-content-center right-panel">

                <div class="login-box">
                    <h2 class="text-center mb-4">Iniciar Sesión</h2>

                    <form action="../../controllers/AuthController.php" method="POST">

                        <input type="email" name="email" class="form-control custom-input mb-3" placeholder="Correo" required>

                        <input type="password" name="password" class="form-control custom-input mb-3" placeholder="Contraseña" required>

                        <button class="btn btn-login btn-block">INGRESAR</button>

                    </form>
                </div>

            </div>

        </div>
    </div>

</body>

</html>
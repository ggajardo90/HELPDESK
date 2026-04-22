<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: views/auth/login.php");
    exit;
}

header("Location: views/dashboard.php");
exit;
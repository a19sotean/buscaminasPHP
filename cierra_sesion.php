<?php
    session_start();
    unset($_SESSION['aTablero']);
    session_destroy();
    header('Location: buscaminas.php');
?>
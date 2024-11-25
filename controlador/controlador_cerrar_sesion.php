<?php
    session_start();
    session_destroy();
    header("location:/CH_ASISTENCIA1/vista/login/login.php");
?>
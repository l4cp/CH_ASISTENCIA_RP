<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de bienvenida</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="public/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="public/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="public/icons/favicon-16x16.png">
    <link rel="manifest" href="public/icons/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- CSS y fuentes -->
    <link rel="stylesheet" href="public/estilos/estilos.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <link href="public/pnotify/css/pnotify.css" rel="stylesheet" />
    <link href="public/pnotify/css/pnotify.buttons.css" rel="stylesheet" />
    <link href="public/pnotify/css/custom.min.css" rel="stylesheet" />
    <script src="public/pnotify/js/jquery.min.js"></script>
    <script src="public/pnotify/js/pnotify.js"></script>
    <script src="public/pnotify/js/pnotify.buttons.js"></script>
</head>

<body>
    <img src="public/images/chim.png" alt="Imagen derecha" class="right-image">
    <img src="public/images/asis.png" alt="Imagen izquierda" class="left-image">
    <h1>¡HOLA! REGISTRA TU ASISTENCIA.</h1>
    <h2 id="fecha"></h2>
    <?php
    include "../CH_ASISTENCIA1/modelo/conexion.php";
    include "../CH_ASISTENCIA1/controlador/controlador_registrar_asistencia.php";
    include "../CH_ASISTENCIA1/controlador/controlador_registrar_salida.php";
    ?>
    <div class="container">
        <a class="acceso" href="vista/login/login.php">Ingresar al sistema</a>
        <p class="dni">INGRESE SU DNI</p>
        <form action="" method="POST">
            <input type="text" placeholder="DNI del empleado" name="txtdni" maxlength="8">
            <div class="botones">
                <button class="entrada" type="submit" name="btnentrada" value="ok">ENTRADA</button>
                <button class="salida" type="submit" name="btnsalida" value="ok">SALIDA</button>
            </div>
        </form>
    </div>

    <script>
        setInterval(() => {
            let fecha = new Date();
            let fechaHora = fecha.toLocaleString();
            document.getElementById("fecha").textContent = fechaHora;
        }, 1000);
    </script>
</body>

</html>

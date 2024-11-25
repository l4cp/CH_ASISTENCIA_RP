<?php
session_start();
if (empty($_SESSION['nombre']) || empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}
?>

<style>
  ul li:nth-child(2).activo {
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- Primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- Luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- Inicio del contenido principal -->
<div class="page-content">
  <h4 class="text-center text-secondary">ACERCA DE NOSOTROS</h4>
    <style>
        /* Estilos para la página "Acerca de" */

        nav ul li a:hover {
            text-decoration: underline;
        }

        #acerca-de {
            background-color: #fff;
            padding: 50px 20px;
            text-align: center;
        }

        #acerca-de h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #003366;
        }

        #acerca-de p {
            font-size: 18px;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto 20px auto;
            color: #555;
        }

        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>


    <section id="acerca-de">
        <div class="contenedor">

            <p>Bienvenidos al <strong>CONSORCIO EDUCATIVO CAYETANO HEREDIA</strong>, una empresa comprometida con ofrecer soluciones innovadoras en [tu industria o sector]. Desde nuestros inicios, hemos trabajado con pasión para brindar a nuestros clientes productos y servicios de alta calidad, adaptados a sus necesidades.</p>
            <p>Nuestra misión es [tu misión]. Nos enfocamos en [lo que hace especial a tu empresa] y trabajamos día a día para superar las expectativas de nuestros clientes.</p>
            <p>Con un equipo altamente calificado y una visión clara de futuro, en <strong>Nombre de tu Empresa</strong> seguimos creciendo y evolucionando para convertirnos en líderes del mercado.</p>
            <p><em>¡Gracias por confiar en nosotros!</em></p>
        </div>
    </section>

    <footer>
        <p>© 2024 Consorcio Educativo Cayetano Heradia. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
<!-- Por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

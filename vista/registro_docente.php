<?php
session_start();
if (empty($_SESSION['nombre']) || empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit(); // Asegúrate de detener la ejecución del script después de redirigir
}
?>

<style>
  ul li:nth-child(3).activo {
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">
    <h4 class="text-center text-secondary">REGISTRO DE DOCENTES</h4>

    <?php
    include '../modelo/conexion.php';
    include "../controlador/controlador_registrar_docente.php"; // Asegúrate de que este archivo maneje el registro de docentes
    ?>

    <div class="row">
      <form action="" method="POST">
        <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
          <input type="text" placeholder="Nombre" class="input input__text" name="txtnombre" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
          <input type="text" placeholder="Especialización" class="input input__text" name="txtespecializacion" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
          <input type="text" placeholder="Contacto" class="input input__text" name="txtcontacto" required>
        </div>
        
        <div class="text-right p-2">
          <a href="docente.php" class="btn btn-secondary btn-rounded">Atrás</a>
          <button type="submit" value="ok" name="btnregistrar" class="btn btn-primary btn-rounded">Registrar</button>
        </div>
      </form>    
    </div>
</div>
<!-- fin del contenido principal -->

<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

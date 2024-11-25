<?php
session_start();
if (empty($_SESSION['nombre']) || empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}
?>

<style>
  ul li:nth-child(3).activo {
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- Cargar topbar y sidebar -->
<?php require('./layout/topbar.php'); ?>
<?php require('./layout/sidebar.php'); ?>

<!-- Contenido principal -->
<div class="page-content">
    <h4 class="text-center text-secondary">REGISTRO DE DESCUENTOS</h4>

    <?php include '../modelo/conexion.php'; ?>
    <?php include "../controlador/controlador_registrar_descuento.php"; ?>

    <div class="row">
      <form action="" method="POST">
        <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
          <select name="txttipo_descuento" class="input input__text" required>
            <option value="">Seleccionar Tipo Descuento</option>
            <option value="Porcentaje">Porcentaje</option>
            <option value="Monto Fijo">Monto Fijo</option>
          </select>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
          <input type="text" placeholder="Valor Descuento" class="input input__text" name="txtvalor_descuento" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
          <input type="text" placeholder="Motivo Descuento" class="input input__text" name="txtmotivo_descuento" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
          <label for="txtfecha_vigencia">Fecha de Vigencia</label>
          <input type="date" class="input input__text" name="txtfecha_vigencia" required>
        </div>
        
        <div class="text-right p-2">
          <a href="descuentos.php" class="btn btn-secondary btn-rounded">Atrás</a>
          <button type="submit" value="ok" name="btnregistrar" class="btn btn-primary btn-rounded">Registrar</button>
        </div>
      </form>    
    </div>
</div>

<!-- Cargar footer -->
<?php require('./layout/footer.php'); ?>

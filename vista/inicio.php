<?php
   session_start();
   if (empty($_SESSION['nombre']) and empty($_SESSION['apellido'])) {
       header('location:login/login.php');
   }

?>

<style>
  ul li:nth-child(1).activo{
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">

    <H4 class="text-center text-secondary">INTRANET - CONSORCIO CAYETANO HEREDIA</H4>

   <?php
    include "../modelo/conexion.php";
    include "../controlador/Controlador_eliminar_asistencia.php";

    $sql = $conexion->query(" SELECT 
    estudiantes.estudiante_id,
    docentes.docente_id,
    usuarios.usuario_id,
    usuarios.nombre_usuario as 'nom_empleado',
    usuarios.apellido_usuario,
    ");

   ?>

    <table class=" table  table-bordered table-hover col-12 " id="example">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">EMPLEADO</th>
      <th scope="col">DNI</th>
      <th scope="col">CARGO</th>
      <th scope="col">INGRESO</th>
      <th scope="col">SALIDA</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    while ($datos=$sql->fetch_object()) {?>
    <tr>
      <td><?= $datos->id_asistencia ?></td>
      <td><?= $datos->nom_empleado . " ". $datos->apellido ?></td>
      <td><?= $datos->dni ?></td>
      <td><?= $datos->nom_cargo ?></td>
      <td><?= $datos->entrada ?></td>
      <td><?= $datos->salida?></td>
      <td>
        <a href="inicio.php?id=<?= $datos->id_asistencia?> " onclick=" advertencia(event)" class="btn btn-danger"><i class="fa-solid fa-eraser"></i></a>
      </td>

    </tr>
    <?php }
    ?>
    
  </tbody>
</table>
</div>
</div>
<!-- fin del contenido principal -->


<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>
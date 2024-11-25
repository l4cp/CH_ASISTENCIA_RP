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

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">
  <h4 class="text-center text-secondary">LISTA DE MATRÍCULAS</h4>

  <?php
  include "../modelo/conexion.php";
  include "../controlador/controlador_modificar_matricula.php";
  include "../controlador/controlador_eliminar_matricula.php";

  $sql = $conexion->query("SELECT
  matriculas.matricula_id,
  CONCAT(estudiantes.nombre, ' ', estudiantes.apellido) as 'nombre_completo',
  cursos.nombre_curso as 'nombre_curso',
  matriculas.costo_matricula,
  matriculas.monto_final,
  matriculas.fecha_matricula
  FROM
  matriculas
  INNER JOIN estudiantes ON matriculas.estudiante_id = estudiantes.estudiante_id
  INNER JOIN cursos ON matriculas.curso_id = cursos.curso_id
  ");
  ?>

  <a href="registro_matricula.php" class="btn btn-primary btn-rounded mb-2"><i class="fa-solid fa-plus"></i> &nbsp;REGISTRAR</a>
  <table class="table table-bordered table-hover w-100" id="example">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">NOMBRE DEL ESTUDIANTE</th>
        <th scope="col">CURSO</th>
        <th scope="col">COSTO MATRÍCULA</th>
        <th scope="col">MONTO FINAL</th>
        <th scope="col">FECHA DE MATRÍCULA</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($datos = $sql->fetch_object()) { ?>
        <tr>
          <td><?= htmlspecialchars($datos->matricula_id) ?></td>
          <td><?= htmlspecialchars($datos->nombre_completo) ?></td>
          <td><?= htmlspecialchars($datos->nombre_curso) ?></td>
          <td><?= htmlspecialchars($datos->costo_matricula) ?></td>
          <td><?= htmlspecialchars($datos->monto_final) ?></td>
          <td><?= htmlspecialchars($datos->fecha_matricula) ?></td>
          <td>
            <a href="" data-toggle="modal" data-target="#exampleModal_<?= $datos->matricula_id ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
            <a href="matriculas.php?id=<?= $datos->matricula_id ?>" onclick="advertencia(event)" class="btn btn-danger"><i class="fa-solid fa-eraser"></i></a>
          </td>
        </tr>
        <!-- Modals de modificación... -->
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

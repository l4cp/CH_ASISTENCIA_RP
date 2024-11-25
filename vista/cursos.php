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

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">
  <h4 class="text-center text-secondary">LISTA DE CURSOS</h4>

  <?php
  include "../modelo/conexion.php";
  include "../controlador/controlador_modificar_curso.php";
  include "../controlador/controlador_eliminar_curso.php";
  include "../controlador/controlador_registrar_curso.php"; 

  $sql = $conexion->query("SELECT
  cursos.curso_id,
  cursos.usuario_id,
  cursos.docente,
  cursos.nombre_curso,
  cursos.duracion,
  cursos.vacantes_ocup,
  cursos.vacantes_max,
  cursos.costo,
  docentes.nombre as 'nombre_doc'
  FROM
  cursos
  INNER JOIN docentes ON cursos.docente = docentes.docente_id
  ");
  ?>
  
  <a href="registro_curso.php" class="btn btn-primary btn-rounded mb-2"><i class="fa-solid fa-plus"></i> &nbsp;REGISTRAR</a>
  <table class="table table-bordered table-hover w-100" id="example">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">NOMBRE DEL CURSO</th>
        <th scope="col">DURACIÓN</th>
        <th scope="col">VACANTES OCUPADAS</th>
        <th scope="col">VACANTES MÁXIMAS</th>
        <th scope="col">COSTO</th>
        <th scope="col">DOCENTE</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($datos = $sql->fetch_object()) { ?>
        <tr>
          <td><?= htmlspecialchars($datos->curso_id) ?></td>
          <td><?= htmlspecialchars($datos->nombre_curso) ?></td>
          <td><?= htmlspecialchars($datos->duracion) ?></td>
          <td><?= htmlspecialchars($datos->vacantes_ocup) ?></td>
          <td><?= htmlspecialchars($datos->vacantes_max) ?></td>
          <td><?= htmlspecialchars($datos->costo) ?></td>
          <td><?= htmlspecialchars($datos->nombre_doc) ?></td>
          <td>
            <a href="" data-toggle="modal" data-target="#exampleModal_<?= $datos->curso_id ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
            <a href="cursos.php?id=<?= $datos->curso_id ?>" onclick="advertencia(event)" class="btn btn-danger"><i class="fa-solid fa-eraser"></i></a>
          </td>
        </tr>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal_<?= $datos->curso_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title w-100" id="exampleModalLabel">Modificar curso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST">
                  <div hidden class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="ID" class="input input__text" name="txtid" value="<?= htmlspecialchars($datos->curso_id) ?>">
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Nombre del curso" class="input input__text" name="txtnombre" value="<?= htmlspecialchars($datos->nombre_curso) ?>">
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12 ">
                    <select name="txtduracion" class="input input__select">
                      <option value="">Seleccionar Duración...</option>
                      <option value="1 MES" <?= ($datos->duracion == '1 MES') ? 'selected' : '' ?>>1 MES</option>
                      <option value="3 MESES" <?= ($datos->duracion == '3 MESES') ? 'selected' : '' ?>>3 MESES</option>
                      <option value="6 MESES" <?= ($datos->duracion == '6 MESES') ? 'selected' : '' ?>>6 MESES</option>
                      <option value="12 MESES" <?= ($datos->duracion == '12 MESES') ? 'selected' : '' ?>>12 MESES</option>
                      <option value="18 MESES" <?= ($datos->duracion == '18 MESES') ? 'selected' : '' ?>>18 MESES</option>
                    </select>
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="number" placeholder="Vacantes ocupadas" class="input input__text" name="txtvacantes_ocup" value="<?= htmlspecialchars($datos->vacantes_ocup) ?>">
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="number" placeholder="Vacantes máximas" class="input input__text" name="txtvacantes_max" value="<?= htmlspecialchars($datos->vacantes_max) ?>">
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Costo" class="input input__text" name="txtcosto" value="<?= htmlspecialchars($datos->costo) ?>">
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <select name="txtdocente" class="input input__select">
                      <option value="">Seleccionar...</option>
                      <?php
                      $sql2 = $conexion->query("SELECT * FROM Docentes");
                      while ($datos2 = $sql2->fetch_object()) { ?>
                        <option value="<?= htmlspecialchars($datos2->docente_id) ?>" <?= ($datos2->docente_id == $datos->docente) ? 'selected' : '' ?>><?= htmlspecialchars($datos2->nombre) ?></option>
                      <?php }
                      ?>
                    </select>
                  </div>
                  <div class="text-right p-2">
                    <a href="cursos.php" class="btn btn-secondary btn-rounded">Atrás</a>
                    <button type="submit" value="ok" name="btnmodificar" class="btn btn-primary btn-rounded">Modificar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </tbody>
  </table>
</div>

<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

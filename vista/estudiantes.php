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
  <h4 class="text-center text-secondary">LISTA DE ESTUDIANTES</h4>

  <?php
  include "../modelo/conexion.php";
  include "../controlador/controlador_modificar_estudiante.php";
  include "../controlador/controlador_eliminar_estudiante.php";
  include "../controlador/controlador_registrar_estudiante.php"; 

  $sql = $conexion->query("SELECT
      estudiante_id,
      nombre,
      apellido,
      tipo_doc,
      Doc_ide,
      celular,
      fecha_registro,
      sede
      FROM Estudiantes
  ");
  ?>

  <a href="registro_estudiante.php" class="btn btn-primary btn-rounded mb-2"><i class="fa-solid fa-plus"></i> &nbsp;REGISTRAR</a>
  <table class="table table-bordered table-hover w-100" id="example">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">NOMBRE</th>
        <th scope="col">APELLIDO</th>
        <th scope="col">TIPO DOC</th>
        <th scope="col">DOCUMENTO ID</th>
        <th scope="col">CELULAR</th>
        <th scope="col">FECHA REGISTRO</th>
        <th scope="col">SEDE</th>
        <th scope="col">ACCIONES</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($datos = $sql->fetch_object()) { ?>
        <tr>
          <td><?= htmlspecialchars($datos->estudiante_id) ?></td>
          <td><?= htmlspecialchars($datos->nombre) ?></td>
          <td><?= htmlspecialchars($datos->apellido) ?></td>
          <td><?= htmlspecialchars($datos->tipo_doc) ?></td>
          <td><?= htmlspecialchars($datos->Doc_ide) ?></td>
          <td><?= htmlspecialchars($datos->celular) ?></td>
          <td><?= htmlspecialchars($datos->fecha_registro) ?></td>
          <td><?= htmlspecialchars($datos->sede) ?></td>
          <td>
            <a href="" data-toggle="modal" data-target="#exampleModal_<?= $datos->estudiante_id ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
            <a href="estudiantes.php?id=<?= $datos->estudiante_id ?>" onclick="advertencia(event)" class="btn btn-danger"><i class="fa-solid fa-eraser"></i></a>
          </td>
        </tr>

        <!-- Modal para modificar estudiante -->
        <div class="modal fade" id="exampleModal_<?= $datos->estudiante_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title w-100" id="exampleModalLabel">Modificar Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST">
                  <input type="hidden" name="txtid" value="<?= htmlspecialchars($datos->estudiante_id) ?>">
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Nombre" class="input input__text" name="txtnombre" value="<?= htmlspecialchars($datos->nombre) ?>" required>
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Apellido" class="input input__text" name="txtapellido" value="<?= htmlspecialchars($datos->apellido) ?>" required>
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <select name="txttipo_doc" class="input input__select" required>
                      <option value="DNI" <?= ($datos->tipo_doc == 'DNI') ? 'selected' : '' ?>>DNI</option>
                      <option value="Carnet de Extranjería" <?= ($datos->tipo_doc == 'Carnet de Extranjería') ? 'selected' : '' ?>>Carnet de Extranjería</option>
                    </select>
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Documento ID" class="input input__text" name="txtdoc_id" value="<?= htmlspecialchars($datos->Doc_ide) ?>" required>
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Celular" class="input input__text" name="txtcelular" value="<?= htmlspecialchars($datos->celular) ?>" required>
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="date" class="input input__text" name="txtfecha_registro" value="<?= htmlspecialchars($datos->fecha_registro) ?>" required>
                  </div>
                  <div class="fl-flex-label mb-4 px-2 col-12">
                    <select name="txtsede" class="input input__select" required>
                      <option value="CHINCHA" <?= ($datos->sede == 'CHINCHA') ? 'selected' : '' ?>>CHINCHA</option>
                      <option value="CAÑETE" <?= ($datos->sede == 'CAÑETE') ? 'selected' : '' ?>>CAÑETE</option>
                      <option value="HUARAL" <?= ($datos->sede == 'HUARAL') ? 'selected' : '' ?>>HUARAL</option>
                      <option value="PISCO" <?= ($datos->sede == 'PISCO') ? 'selected' : '' ?>>PISCO</option>
                    </select>
                  </div>
                  <div class="text-right p-2">
                    <a href="estudiantes.php" class="btn btn-secondary btn-rounded">Atrás</a>
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

<!-- Cargar footer -->
<?php require('./layout/footer.php'); ?>

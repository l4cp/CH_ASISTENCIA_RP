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
  <h4 class="text-center text-secondary">LISTA DE DESCUENTOS</h4>

  <?php
  include "../modelo/conexion.php";
  include "../controlador/controlador_modificar_descuento.php";
  include "../controlador/controlador_eliminar_descuento.php";
  include "../controlador/controlador_registrar_descuento.php"; 

  // Consulta para obtener todos los descuentos
  $sql = $conexion->query("
    SELECT d.descuento_id, d.tipo_descuento, d.valor_descuento, d.motivo_descuento, d.fecha_vigencia
    FROM Descuentos d
  ");
  ?>

  <a href="registro_descuento.php" class="btn btn-primary btn-rounded mb-2"><i class="fa-solid fa-plus"></i> &nbsp;REGISTRAR</a>
  <table class="table table-bordered table-hover w-100" id="example">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">TIPO DE DESCUENTO</th>
        <th scope="col">VALOR DEL DESCUENTO</th>
        <th scope="col">MOTIVO</th>
        <th scope="col">FECHA DE VIGENCIA</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($datos = $sql->fetch_object()) { ?>
        <tr>
          <td><?= htmlspecialchars($datos->descuento_id ?? '') ?></td>
          <td><?= htmlspecialchars($datos->tipo_descuento ?? '') ?></td>
          <td><?= htmlspecialchars($datos->valor_descuento ?? '') ?></td>
          <td><?= htmlspecialchars($datos->motivo_descuento ?? '') ?></td>
          <td><?= htmlspecialchars($datos->fecha_vigencia ?? '') ?></td>
          <td>
            <a href="#" data-toggle="modal" data-target="#modalModificar_<?= $datos->descuento_id ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
            <a href="descuentos.php?id=<?= $datos->descuento_id ?>" onclick="advertencia(event)" class="btn btn-danger"><i class="fa-solid fa-eraser"></i></a>
          </td>
        </tr>

        <!-- Modal para modificar descuento -->
        <div class="modal fade" id="modalModificar_<?= $datos->descuento_id ?>" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title w-100" id="modalModificarLabel">Modificar Descuento - ID: <?= htmlspecialchars($datos->descuento_id ?? '') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST">
                  <input type="hidden" name="txtid" value="<?= htmlspecialchars($datos->descuento_id ?? '') ?>">
                  <div class="form-group mb-4">
                    <label for="txttipo_descuento">Tipo de Descuento</label>
                    <select name="txttipo_descuento" class="form-control">
                      <option value="Porcentaje" <?= ($datos->tipo_descuento == 'Porcentaje') ? 'selected' : '' ?>>Porcentaje</option>
                      <option value="Monto Fijo" <?= ($datos->tipo_descuento == 'Monto Fijo') ? 'selected' : '' ?>>Monto Fijo</option>
                    </select>
                  </div>
                  <div class="form-group mb-4">
                    <label for="txtvalor_descuento">Valor del Descuento</label>
                    <input type="number" class="form-control" name="txtvalor_descuento" value="<?= htmlspecialchars($datos->valor_descuento ?? '') ?>" step="0.01" required>
                  </div>
                  <div class="form-group mb-4">
                    <label for="txtmotivo_descuento">Motivo del Descuento</label>
                    <input type="text" class="form-control" name="txtmotivo_descuento" value="<?= htmlspecialchars($datos->motivo_descuento ?? '') ?>" required>
                  </div>
                  <div class="form-group mb-4">
                    <label for="txtfecha_vigencia">Fecha de Vigencia</label>
                    <input type="date" class="form-control" name="txtfecha_vigencia" value="<?= htmlspecialchars($datos->fecha_vigencia ?? '') ?>" required>
                  </div>
                  <div class="text-right p-2">
                    <a href="descuentos.php" class="btn btn-secondary btn-rounded">Atrás</a>
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

<!-- Por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

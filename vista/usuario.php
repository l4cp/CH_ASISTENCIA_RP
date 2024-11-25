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
  <h4 class="text-center text-secondary">LISTA DE USUARIOS</h4>

  <?php
  include "../modelo/conexion.php";
  include "../controlador/controlador_modificar_usuario.php";
  include "../controlador/controlador_eliminar_usuario.php";

  // Cambié el nombre de la tabla a 'Usuarios'
  $sql = $conexion->query("SELECT * FROM Usuarios");
  ?>

  <a href="registro_usuario.php" class="btn btn-primary btn-rounded mb-2"><i class="fa-solid fa-plus"></i> &nbsp;REGISTRAR</a>
  <table class="table table-bordered table-hover w-100" id="example">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">NOMBRES</th>
        <th scope="col">APELLIDOS</th>
        <th scope="col">USUARIO</th>
        <th scope="col">TELEFONO</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php while ($datos = $sql->fetch_object()) { ?>
        <tr>
          <td><?= $datos->usuario_id ?></td>
          <td><?= $datos->nombre_usuario ?></td>
          <td><?= $datos->apellido_usuario ?></td>
          <td><?= $datos->usuarme ?></td>
          <td><?= $datos->celular ?></td>
          <td>
            <a href="" data-toggle="modal" data-target="#exampleModal_<?= $datos->usuario_id ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
            <a href="usuario.php?id=<?= $datos->usuario_id ?>" onclick="advertencia(event)" class="btn btn-danger"><i class="fa-solid fa-eraser"></i></a>
          </td>
        </tr>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal_<?= $datos->usuario_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header d-flex justify-content-between">
                <h5 class="modal-title w-100" id="exampleModalLabel">Modificar usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="" method="POST">
                  <div hidden class="fl-flex-label mb-5 px-2 col-12">
                    <input type="text" placeholder="ID" class="input input__text" name="txtid" value="<?= $datos->usuario_id?>">
                  </div>
                  <div class="fl-flex-label mb-5 px-2 col-12">
                    <input type="text" placeholder="Nombre" class="input input__text" name="txtnombre" value="<?= $datos->nombre_usuario?>">
                  </div>
                  <div class="fl-flex-label mb-5 px-2 col-12">
                    <input type="text" placeholder="Apellido" class="input input__text" name="txtapellido" value="<?= $datos->apellido_usuario?>">
                  </div>
                  <div class="fl-flex-label mb-5 px-2 col-12">
                    <input type="text" placeholder="Usuario" class="input input__text" name="txtusuario" value="<?= $datos->usuarme?>">
                  </div>
                  <div class="fl-flex-label mb-5 px-2 col-12">
                    <input type="text" placeholder="Celular" class="input input__text" name="txtcelular" value="<?= $datos->celular?>">
                  </div>
                  <div class="text-right p-2">
                    <a href="usuario.php" class="btn btn-secondary btn-rounded">Atrás</a>
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

<!-- por ultimo se carga el footer -->
<?php require('./layout/footer.php'); ?>

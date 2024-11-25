<?php
session_start();
if (empty($_SESSION['nombre']) || empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}

$id = $_SESSION["id"];
?>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">
    <h4 class="text-center text-secondary">CAMBIAR CONTRASEÑA</h4>

    <?php
    include '../modelo/conexion.php';
    include "../controlador/controlador_cambiar_clave.php";

    // Usar consulta preparada para evitar inyecciones SQL
    $sql = $conexion->prepare("SELECT * FROM usuarios WHERE usuario_id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();
    ?>

    <div class="row">
        <form action="" method="POST">
            <?php while ($datos = $result->fetch_object()) { ?>
                <div hidden class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                    <input type="text" placeholder="ID" class="input input__text" name="txtid" value="<?= htmlspecialchars($datos->usuario_id) ?>">
                </div>
                <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Contraseña Actual" class="input input__text" name="txtclaveactual" value="">
                </div>
                <div class="fl-flex-label mb-4 px-2 col-12">
                    <input type="text" placeholder="Contraseña Nueva" class="input input__text" name="txtclavenueva" value="">
                </div>
                <div class="text-right p-2">
                    <button type="submit" value="ok" name="btnmodificar" class="btn btn-primary btn-rounded">Modificar</button>
                </div>
            <?php } ?>
        </form>
    </div>
</div>
<!-- fin del contenido principal -->

<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

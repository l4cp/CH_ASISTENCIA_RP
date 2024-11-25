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
    <h4 class="text-center text-secondary">PERFIL DE USUARIO</h4>

    <?php
    include '../modelo/conexion.php';
    
    // Usar consulta preparada para evitar inyecciones SQL
    $sql = $conexion->prepare("SELECT * FROM usuarios WHERE usuario_id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();

    // Verificar si se ha enviado el formulario
    if (isset($_POST['btnmodificar'])) {
        $nombre = trim($_POST['txtnombre']);
        $apellido = trim($_POST['txtapellido']);
        $usuario = trim($_POST['txtusuario']);
        
        // Validar que los campos no estén vacíos
        if (!empty($nombre) && !empty($apellido) && !empty($usuario)) {
            // Actualizar el perfil en la base de datos
            $updateSql = $conexion->prepare("UPDATE usuarios SET nombre_usuario = ?, apellido_usuario = ?, usuarme = ? WHERE usuario_id = ?");
            $updateSql->bind_param("sssi", $nombre, $apellido, $usuario, $id);

            if ($updateSql->execute()) {
                echo '<script>
                    $(function() {
                        new PNotify({
                            title: "¡CORRECTO!",
                            type: "success",
                            text: "¡Perfil modificado correctamente!",
                            styling: "bootstrap3"
                        });
                    });
                </script>';
            } else {
                echo '<script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡Error al modificar el perfil!",
                            styling: "bootstrap3"
                        });
                    });
                </script>';
            }

            $updateSql->close();
        } else {
            echo '<script>
                $(function() {
                    new PNotify({
                        title: "¡ERROR!",
                        type: "error",
                        text: "¡Todos los campos deben estar llenos!",
                        styling: "bootstrap3"
                    });
                });
            </script>';
        }
    }
    ?>

    <div class="row">
        <form action="" method="POST">
            <?php while ($datos = $result->fetch_object()) { ?>
                <div hidden class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                    <input type="text" placeholder="ID" class="input input__text" name="txtid" value="<?= htmlspecialchars($datos->usuario_id) ?>">
                </div>
                <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                    <input type="text" placeholder="Nombre" class="input input__text" name="txtnombre" value="<?= htmlspecialchars($datos->nombre_usuario) ?>" required>
                </div>
                <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                    <input type="text" placeholder="Apellido" class="input input__text" name="txtapellido" value="<?= htmlspecialchars($datos->apellido_usuario) ?>" required>
                </div>
                <div class="fl-flex-label mb-4 px-2 col-12 col-md-6">
                    <input type="text" placeholder="Usuario" class="input input__text" name="txtusuario" value="<?= htmlspecialchars($datos->usuarme) ?>" required>
                </div>
                <div class="text-right p-2">
                    <button type="submit" name="btnmodificar" class="btn btn-primary btn-rounded">Modificar</button>
                </div>
            <?php } ?>
        </form>
    </div>
</div>
<!-- fin del contenido principal -->

<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

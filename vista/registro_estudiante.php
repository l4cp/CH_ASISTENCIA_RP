<?php
session_start();
if (empty($_SESSION['nombre']) || empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}
?>

<style>
  ul li:nth-child(4).activo {
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- Primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- Luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- Inicio del contenido principal -->
<div class="page-content">

    <h4 class="text-center text-secondary">REGISTRO DE ESTUDIANTES</h4>

    <?php
    include '../modelo/conexion.php';

    // Verificar si se ha enviado el formulario
    if (isset($_POST['btnregistrar'])) {
        $nombre = trim($_POST['txtnombre']);
        $apellido = trim($_POST['txtapellido']);
        $tipo_doc = trim($_POST['txttipo_doc']);
        $doc_id = trim($_POST['txtdoc_ide']);
        $celular = trim($_POST['txtcelular']);
        $sede = trim($_POST['txtsede']);

        // Validar que todos los campos están llenos
        if (!empty($nombre) && !empty($apellido) && !empty($tipo_doc) && !empty($doc_id) && !empty($celular) && !empty($sede)) {
            // Verificar si ya existe un estudiante con el mismo Documento ID
            $verificarDocID = $conexion->prepare("SELECT COUNT(*) AS total FROM Estudiantes WHERE Doc_ide = ?");
            $verificarDocID->bind_param("s", $doc_id);
            $verificarDocID->execute();
            $resultado = $verificarDocID->get_result();

            if ($resultado->fetch_object()->total > 0) {
                echo '<script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡El estudiante con Documento ID ' . htmlspecialchars($doc_id) . ' ya existe!",
                            styling: "bootstrap3"
                        });
                    });
                </script>';
            } else {
                // Registrar el nuevo estudiante
                $sql = $conexion->prepare("INSERT INTO Estudiantes (nombre, apellido, tipo_doc, Doc_ide, celular, fecha_registro, sede) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
                $sql->bind_param("ssssss", $nombre, $apellido, $tipo_doc, $doc_id, $celular, $sede);

                if ($sql->execute()) {
                    echo '<script>
                        $(function() {
                            new PNotify({
                                title: "¡CORRECTO!",
                                type: "success",
                                text: "¡Estudiante ' . htmlspecialchars($nombre) . ' registrado correctamente!",
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
                                text: "¡Error al registrar el estudiante ' . htmlspecialchars($nombre) . '!",
                                styling: "bootstrap3"
                            });
                        });
                    </script>';
                }

                $sql->close();
            }

            $verificarDocID->close();
        } else {
            echo '<script>
                $(function() {
                    new PNotify({
                        title: "¡ERROR!",
                        type: "error",
                        text: "¡Todos los campos deben ser completados!",
                        styling: "bootstrap3"
                    });
                });
            </script>';
        }
    }
    ?>

    <div class="row">
      <form action="" method="POST">
        <div class="fl-flex-label mb-4 px-2 col-12">
          <input type="text" placeholder="Nombre del Estudiante" class="input input__text" name="txtnombre" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12">
          <input type="text" placeholder="Apellido del Estudiante" class="input input__text" name="txtapellido" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12">
          <select name="txttipo_doc" class="input input__select" required>
            <option value="">Tipo de Documento</option>
            <option value="DNI">DNI</option>
            <option value="Carnet de Extranjería">Carnet de Extranjería</option>
          </select>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12">
          <input type="text" placeholder="Número de Documento" class="input input__text" name="txtdoc_ide" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12">
          <input type="text" placeholder="Celular" class="input input__text" name="txtcelular" required>
        </div>
        <div class="fl-flex-label mb-4 px-2 col-12">
          <select name="txtsede" class="input input__select" required>
            <option value="">Seleccionar Sede...</option>
            <option value="CHINCHA">CHINCHA</option>
            <option value="CAÑETE">CAÑETE</option>
            <option value="HUARAL">HUARAL</option>
            <option value="PISCO">PISCO</option>
          </select>
        </div>
        <div class="text-right p-2">
          <a href="estudiantes.php" class="btn btn-secondary btn-rounded">Atrás</a>
          <button type="submit" name="btnregistrar" class="btn btn-primary btn-rounded">Registrar</button>
        </div>
      </form>    
    </div>

</div>
<!-- Fin del contenido principal -->

<!-- Por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

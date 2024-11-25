<?php
// Verifica si el formulario ha sido enviado
if (!empty($_POST["btnmodificar"])) {
    // Verifica si los campos necesarios no están vacíos
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtapellido"]) && isset($_POST["txttipo_doc"]) && !empty($_POST["txtdoc_id"]) && isset($_POST["txtcelular"]) && !empty($_POST["txtsede"])) {
        // Obtener los datos del formulario
        $id = (int)$_POST["txtid"]; // Convierte el ID a entero para evitar inyecciones SQL
        $nombre = htmlspecialchars(trim($_POST["txtnombre"]));
        $apellido = htmlspecialchars(trim($_POST["txtapellido"]));
        $tipo_doc = htmlspecialchars(trim($_POST["txttipo_doc"]));
        $doc_id = htmlspecialchars(trim($_POST["txtdoc_id"]));
        $celular = htmlspecialchars(trim($_POST["txtcelular"]));
        $sede = htmlspecialchars(trim($_POST["txtsede"]));

        // Conexión a la base de datos
        include '../modelo/conexion.php';

        // Verificar si otro estudiante con el mismo Documento ID ya existe, excluyendo el estudiante actual
        $verificarDocID = $conexion->prepare("SELECT COUNT(*) as total FROM Estudiantes WHERE Doc_ide = ? AND estudiante_id != ?");
        $verificarDocID->bind_param("si", $doc_id, $id); // Excluye el estudiante actual de la verificación
        $verificarDocID->execute();
        $resultado = $verificarDocID->get_result();

        if ($resultado->fetch_object()->total > 0) {
            // Mensaje de error si el Documento ID ya existe
            ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡ERROR!",
                        type: "error",
                        text: "¡El estudiante con Documento ID <?= htmlspecialchars($doc_id) ?> ya existe!",
                        styling: "bootstrap3"
                    });
                });
                setTimeout(() => {
                    window.history.replaceState(null, null, window.location.pathname);
                }, 2000);
            </script>
            <?php
        } else {
            // Preparar y ejecutar la consulta de actualización
            $sql = $conexion->prepare("UPDATE Estudiantes SET nombre = ?, apellido = ?, tipo_doc = ?, Doc_ide = ?, celular = ?, sede = ? WHERE estudiante_id = ?");
            $sql->bind_param("ssssssi", $nombre, $apellido, $tipo_doc, $doc_id, $celular, $sede, $id);

            if ($sql->execute()) {
                // Mensaje de éxito
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡CORRECTO!",
                            type: "success",
                            text: "¡Estudiante modificado correctamente!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.history.replaceState(null, null, window.location.pathname);
                    }, 2000);
                </script>
                <?php
            } else {
                // Mensaje de error al modificar
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡Error al modificar el estudiante!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.history.replaceState(null, null, window.location.pathname);
                    }, 2000);
                </script>
                <?php
            }

            // Cierra la consulta
            $sql->close();
        }

        // Cierra la consulta de verificación
        $verificarDocID->close();
    } else {
        // Mensaje de error si alguno de los campos está vacío
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Todos los campos deben estar llenos!",
                    styling: "bootstrap3"
                });
            });
            setTimeout(() => {
                window.history.replaceState(null, null, window.location.pathname);
            }, 2000);
        </script>
        <?php
    }
}
?>

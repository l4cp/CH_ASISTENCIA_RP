<?php
// Incluye la conexión a la base de datos
include '../modelo/conexion.php';

if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtapellido"]) && !empty($_POST["txttipo_doc"]) && !empty($_POST["txtdoc_ide"]) && !empty($_POST["txtcelular"]) && !empty($_POST["txtsede"])) {
        $nombre = trim($_POST["txtnombre"]);
        $apellido = trim($_POST["txtapellido"]);
        $tipo_doc = trim($_POST["txttipo_doc"]);
        $doc_id = trim($_POST["txtdoc_ide"]);
        $celular = trim($_POST["txtcelular"]);
        $sede = trim($_POST["txtsede"]);
        $fecha_registro = date('Y-m-d'); // Fecha actual

        // Verifica si ya existe un estudiante con el mismo documento ID
        $verificarDocID = $conexion->prepare("SELECT COUNT(*) as total FROM Estudiantes WHERE Doc_ide = ?");
        if ($verificarDocID) {
            $verificarDocID->bind_param("s", $doc_id);
            $verificarDocID->execute();
            $resultado = $verificarDocID->get_result();

            if ($resultado->fetch_object()->total > 0) {
                // El estudiante ya existe
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
                // El estudiante no existe, registrar el nuevo estudiante
                $sql = $conexion->prepare("INSERT INTO Estudiantes (nombre, apellido, tipo_doc, Doc_ide, celular, fecha_registro, sede) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($sql) {
                    $sql->bind_param("sssssss", $nombre, $apellido, $tipo_doc, $doc_id, $celular, $fecha_registro, $sede);

                    if ($sql->execute()) {
                        // Mensaje de éxito
                        ?>
                        <script>
                            $(function() {
                                new PNotify({
                                    title: "¡CORRECTO!",
                                    type: "success",
                                    text: "¡Estudiante <?= htmlspecialchars($nombre) ?> registrado correctamente!",
                                    styling: "bootstrap3"
                                });
                            });
                            setTimeout(() => {
                                window.history.replaceState(null, null, window.location.pathname);
                            }, 2000);
                        </script>
                        <?php
                    } else {
                        // Mensaje de error al registrar el estudiante
                        ?>
                        <script>
                            $(function() {
                                new PNotify({
                                    title: "¡ERROR!",
                                    type: "error",
                                    text: "¡Error al registrar el estudiante <?= htmlspecialchars($nombre) ?>!",
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
                } else {
                    echo "Error preparando la consulta de registro: " . $conexion->error;
                }
            }

            // Cierra la consulta de verificación
            $verificarDocID->close();
        } else {
            echo "Error preparando la consulta de verificación: " . $conexion->error;
        }
    } else {
        // Mensaje de error si alguno de los campos está vacío
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Todos los campos deben ser completados!",
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

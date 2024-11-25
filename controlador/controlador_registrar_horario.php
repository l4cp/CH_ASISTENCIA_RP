<?php
// Incluye la conexión a la base de datos
include '../modelo/conexion.php';

if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtduracion"]) && !empty($_POST["txtvacantes_max"]) && !empty($_POST["txtcosto"]) && !empty($_POST["txtdocente"])) {
        $id_curso = (int)$_POST["id_curso"]; // El id del curso que estás editando
        $nombre = trim($_POST["txtnombre"]);
        $duracion = trim($_POST["txtduracion"]); // Obtener duración del curso
        $vacantes_max = (int)$_POST["txtvacantes_max"];
        $costo = trim($_POST["txtcosto"]);
        $vacantes_ocup = 0; // Valor por defecto para vacantes ocupadas

        // Obtener usuario_id y docente_id (ajusta según tus necesidades)
        $usuario_id = 1; // Por ejemplo, puedes obtenerlo de la sesión
        $docente = (int)$_POST["txtdocente"]; // Igualmente, ajústalo según tu lógica

        // Verifica si otro curso con el mismo nombre ya existe, excluyendo el curso que estás editando
        $verificarNombre = $conexion->prepare("SELECT count(*) as total FROM Cursos WHERE nombre_curso = ? AND id != ?");
        if ($verificarNombre) {
            $verificarNombre->bind_param("si", $nombre, $id_curso);
            $verificarNombre->execute();
            $resultado = $verificarNombre->get_result();

            if ($resultado->fetch_object()->total > 0) {
                // El curso ya existe
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡El curso <?= htmlspecialchars($nombre) ?> ya existe!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.history.replaceState(null, null, window.location.pathname);
                    }, 2000);
                </script>
                <?php
            } else {
                // El curso no existe, actualizar el curso existente
                $sql = $conexion->prepare("UPDATE Cursos SET usuario_id = ?, docente = ?, nombre_curso = ?, duracion = ?, vacantes_ocup = ?, vacantes_max = ?, costo = ? WHERE id = ?");
                if ($sql) {
                    $sql->bind_param("iissiid", $usuario_id, $docente, $nombre, $duracion, $vacantes_ocup, $vacantes_max, $costo, $id_curso);

                    if ($sql->execute()) {
                        // Mensaje de éxito
                        ?>
                        <script>
                            $(function() {
                                new PNotify({
                                    title: "¡CORRECTO!",
                                    type: "success",
                                    text: "¡Curso <?= htmlspecialchars($nombre) ?> actualizado correctamente!",
                                    styling: "bootstrap3"
                                });
                            });
                            setTimeout(() => {
                                window.history.replaceState(null, null, window.location.pathname);
                            }, 2000);
                        </script>
                        <?php
                    } else {
                        // Mensaje de error al actualizar el curso
                        ?>
                        <script>
                            $(function() {
                                new PNotify({
                                    title: "¡ERROR!",
                                    type: "error",
                                    text: "¡Error al actualizar el curso <?= htmlspecialchars($nombre) ?>!",
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
                    echo "Error preparando la consulta de actualización: " . $conexion->error;
                }
            }

            // Cierra la consulta de verificación
            $verificarNombre->close();
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

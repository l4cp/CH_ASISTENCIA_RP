<?php
// Verifica si el formulario ha sido enviado
if (!empty($_POST["btnmodificar"])) {
    // Verifica si los campos necesarios no están vacíos
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtduracion"]) && isset($_POST["txtvacantes_max"]) && isset($_POST["txtcosto"])) {
        // Obtener los datos del formulario
        $id = (int)$_POST["txtid"]; // Asegúrate de convertir el id a entero para evitar inyecciones SQL
        $nombre = htmlspecialchars($_POST["txtnombre"]);
        $duracion = htmlspecialchars($_POST["txtduracion"]);
        $vacantesMax = (int)$_POST["txtvacantes_max"];
        $costo = (float)$_POST["txtcosto"];

        // Conexión a la base de datos
        include '../modelo/conexion.php';

        // Verificar si otro curso con el mismo nombre ya existe, excluyendo el curso actual
        $verificarNombre = $conexion->prepare("SELECT count(*) as total FROM Cursos WHERE nombre_curso = ? AND curso_id != ?");
        $verificarNombre->bind_param("si", $nombre, $id); // Excluye el curso actual de la verificación
        $verificarNombre->execute();
        $resultado = $verificarNombre->get_result();

        if ($resultado->fetch_object()->total > 0) { ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡ERROR!",
                        type: "error",
                        text: "¡El curso con el nombre <?= htmlspecialchars($nombre) ?> ya existe!",
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
            $sql = $conexion->prepare("UPDATE Cursos SET nombre_curso = ?, duracion = ?, vacantes_max = ?, costo = ? WHERE curso_id = ?");
            $sql->bind_param("ssidi", $nombre, $duracion, $vacantesMax, $costo, $id);

            if ($sql->execute()) {
                // Mensaje de éxito
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡CORRECTO!",
                            type: "success",
                            text: "¡Curso modificado correctamente!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.history.replaceState(null, null, window.location.pathname);
                    }, 2000);
                </script>
                <?php
            } else {
                // Mensaje de error
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡Error al modificar el curso!",
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
        $verificarNombre->close();
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

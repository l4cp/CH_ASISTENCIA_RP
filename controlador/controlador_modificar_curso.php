<?php

// Incluye la conexión a la base de datos
include '../modelo/conexion.php';

// Verifica si el formulario ha sido enviado
if (!empty($_POST["btnmodificar"])) {
    // Obtener los datos del formulario
    $id = (int)$_POST["txtid"]; // Asegúrate de convertir el id a entero para evitar inyecciones SQL
    $nombre = htmlspecialchars($_POST["txtnombre"]);
    $duracion = htmlspecialchars($_POST["txtduracion"]);
    $vacantesMax = isset($_POST["txtvacantes_max"]) ? (int)$_POST["txtvacantes_max"] : null; // Permite que vacantes_max pueda estar vacío
    $costo = isset($_POST["txtcosto"]) ? (float)$_POST["txtcosto"] : null; // Permite que costo pueda estar vacío
    $docenteId = isset($_POST["txtdocente_id"]) ? (int)$_POST["txtdocente_id"] : null; // Permite que docente pueda estar vacío

    // Consulta para obtener las aulas
    $aulas_query = "SELECT aula_id, nombre_aula FROM Aulas";
    $resultado_aulas = $conexion->query($aulas_query);

    // Consulta para obtener los docentes
    $docentes_query = "SELECT docente_id, nombre FROM Docentes";
    $resultado_docentes = $conexion->query($docentes_query);

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
        // Construir la consulta de actualización de manera dinámica
        $sql = "UPDATE Cursos SET nombre_curso = ?, duracion = ?, vacantes_max = ?, costo = ?";

        // Agregar el campo del docente solo si se proporciona
        if ($docenteId !== null) {
            $sql .= ", docente = ?";
        }

        $sql .= " WHERE curso_id = ?";

        $stmt = $conexion->prepare($sql);

        // Prepara los tipos de parámetros
        if ($docenteId !== null) {
            $stmt->bind_param("ssidi", $nombre, $duracion, $vacantesMax, $costo, $docenteId, $id);
        } else {
            // Si no se proporciona el ID del docente, ajustamos los parámetros
            $stmt->bind_param("ssidi", $nombre, $duracion, $vacantesMax, $costo, $id);
        }

        if ($stmt->execute()) {
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
        $stmt->close();
    }
    // Cierra la consulta de verificación
    $verificarNombre->close();
}
?>

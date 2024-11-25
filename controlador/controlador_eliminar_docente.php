<?php
// Verifica si el parámetro 'id' está presente en la URL
if (!empty($_GET["id"])) {
    // Obtiene el id desde la URL y asegúrate de convertirlo a entero para evitar inyecciones SQL
    $id = (int)$_GET["id"];

    // Conexión a la base de datos
    include '../modelo/conexion.php';

    // Preparar y ejecutar la consulta de eliminación
    $sql = $conexion->prepare("DELETE FROM Docentes WHERE docente_id = ?");
    $sql->bind_param("i", $id);

    if ($sql->execute()) {
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡CORRECTO!",
                    type: "success",
                    text: "¡Docente eliminado correctamente!",
                    styling: "bootstrap3"
                });
            });
            setTimeout(() => {
                window.history.replaceState(null, null, window.location.pathname);
            }, 0);
        </script>
        <?php
    } else {
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Error al eliminar el docente!",
                    styling: "bootstrap3"
                });
            });
            setTimeout(() => {
                window.history.replaceState(null, null, window.location.pathname);
            }, 0);
        </script>
        <?php
    }

    $sql->close();
}
?>

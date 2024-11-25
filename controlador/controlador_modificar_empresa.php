<?php
if (!empty($_POST["btnmodificar"])) {
    // Verifica que todos los campos necesarios no estén vacíos
    if (!empty($_POST["txtid"]) && !empty($_POST["txtnombre"]) && !empty($_POST["txttelefono"]) && !empty($_POST["txtubicacion"]) && !empty($_POST["txtruc"])) {
        // Escapa y limpia las entradas para prevenir inyecciones SQL
        $id = htmlspecialchars($_POST["txtid"]);
        $nombre = htmlspecialchars($_POST["txtnombre"]);
        $telefono = htmlspecialchars($_POST["txttelefono"]);
        $ubicacion = htmlspecialchars($_POST["txtubicacion"]);
        $ruc = htmlspecialchars($_POST["txtruc"]);

        // Preparar la consulta de actualización
        $sql = $conexion->prepare("UPDATE empresa SET nombre = ?, telefono = ?, ubicacion = ?, ruc = ? WHERE id_empresa = ?");
        $sql->bind_param("ssssi", $nombre, $telefono, $ubicacion, $ruc, $id);

        // Ejecutar la consulta y verificar si fue exitosa
        if ($sql->execute()) {
            ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡CORRECTO!",
                        type: "success",
                        text: "¡Datos de la empresa actualizados correctamente!",
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
                        text: "¡Error al actualizar los datos de la empresa!",
                        styling: "bootstrap3"
                    });
                });
                setTimeout(() => {
                    window.history.replaceState(null, null, window.location.pathname);
                }, 0);
            </script>
            <?php
        }

        // Cierra la consulta
        $sql->close();
    } else {
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Todos los campos son obligatorios!",
                    styling: "bootstrap3"
                });
            });
            setTimeout(() => {
                window.history.replaceState(null, null, window.location.pathname);
            }, 0);
        </script>
        <?php
    }
}
?>

<?php
if (!empty($_POST["btnmodificar"])) {
    if (!empty($_POST["txtid"]) && !empty($_POST["txtnombre"]) && !empty($_POST["txtespecializacion"]) && !empty($_POST["txtcontacto"])) {
        
        // Sanear y asignar las variables
        $id = (int)$_POST["txtid"]; // Asegúrate de convertir el ID a entero
        $nombre = htmlspecialchars($_POST["txtnombre"]);
        $especializacion = htmlspecialchars($_POST["txtespecializacion"]);
        $contacto = htmlspecialchars($_POST["txtcontacto"]);
        
        // Conexión a la base de datos
        include '../modelo/conexion.php';

        // Preparar y ejecutar la consulta de actualización
        $sql = $conexion->prepare("UPDATE Docentes SET nombre = ?, especializacion = ?, contacto = ? WHERE docente_id = ?");
        $sql->bind_param("sssi", $nombre, $especializacion, $contacto, $id);

        if ($sql->execute()) {
            ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡CORRECTO!",
                        type: "success",
                        text: "¡El docente ha sido modificado correctamente!",
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
                        text: "¡Error al modificar el docente!",
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
    } else {
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
            }, 0);
        </script>
        <?php
    }
}
?>

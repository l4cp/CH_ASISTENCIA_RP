<?php
include '../modelo/conexion.php'; // Asegúrate de que la conexión a la base de datos esté incluida

if (!empty($_POST["btnmodificar"])) {
    // Verifica que todos los campos necesarios no estén vacíos
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtapellido"]) && !empty($_POST["txtusuario"])) {
        // Escapa los valores recibidos para prevenir inyecciones SQL
        $id = htmlspecialchars($_POST["txtid"]); // Asegúrate de que el ID se esté recibiendo
        $nombre = htmlspecialchars(trim($_POST["txtnombre"]));
        $apellido = htmlspecialchars(trim($_POST["txtapellido"]));
        $usuario = htmlspecialchars(trim($_POST["txtusuario"]));

        // Prepara la consulta SQL para actualizar los datos del usuario
        $sql = $conexion->prepare("UPDATE usuarios SET nombre_usuario=?, apellido_usuario=?, usuario=? WHERE usuario_id=?");
        $sql->bind_param("sssi", $nombre, $apellido, $usuario, $id);

        // Ejecuta la consulta y verifica si fue exitosa
        if ($sql->execute()) {
            ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡CORRECTO!",
                        type: "success",
                        text: "¡Perfil modificado correctamente!",
                        styling: "bootstrap3"
                    });
                });
                setTimeout(() => {
                    window.history.replaceState(null, null, window.location.pathname);
                }, 2000);
            </script>
            <?php
        } else {
            ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡ERROR!",
                        type: "error",
                        text: "¡Error al modificar el perfil!",
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
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Todos los campos son requeridos!",
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

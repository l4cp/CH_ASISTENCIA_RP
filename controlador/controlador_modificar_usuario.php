<?php
include "../modelo/conexion.php";

if (!empty($_POST["btnmodificar"])) { // Verifica si se ha enviado el formulario
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtapellido"]) && !empty($_POST["txtusuario"]) && !empty($_POST["txtcelular"])) { // Verifica si los campos no están vacíos
        $nombre = htmlspecialchars($_POST["txtnombre"]);
        $apellido = htmlspecialchars($_POST["txtapellido"]);
        $usuario = htmlspecialchars($_POST["txtusuario"]);
        $celular = htmlspecialchars($_POST["txtcelular"]);
        $id = (int)$_POST["txtid"];

        // Verificar si el nombre de usuario ya existe con un id diferente usando consultas preparadas
        $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM Usuarios WHERE usuarme = ? AND usuario_id != ?");
        $stmt->bind_param("si", $usuario, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_object();
            if ($row->total > 0) {
                ?>
                <script>
                    $(document).ready(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡El nombre de usuario <?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?> ya existe!",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            } else {
                // Preparar y ejecutar la actualización del usuario
                $stmt_update = $conexion->prepare("UPDATE Usuarios SET nombre_usuario = ?, apellido_usuario = ?, usuarme = ?, celular = ? WHERE usuario_id = ?");
                $stmt_update->bind_param("ssssi", $nombre, $apellido, $usuario, $celular, $id);

                if ($stmt_update->execute()) {
                    ?>
                    <script>
                        $(document).ready(function() {
                            new PNotify({
                                title: "¡CORRECTO!",
                                type: "success",
                                text: "¡El usuario se ha modificado correctamente!",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        $(document).ready(function() {
                            new PNotify({
                                title: "¡INCORRECTO!",
                                type: "error",
                                text: "¡Error al modificar el usuario!",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
                    <?php
                }
                $stmt_update->close();
            }
        } else {
            echo "Error en la consulta: " . $conexion->error;
        }
        $stmt->close();
    } else {
        ?>
        <script>
            $(document).ready(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Los campos están vacíos!",
                    styling: "bootstrap3"
                });
            });
        </script>
        <?php
    }
    // Script para limpiar el historial del navegador después de la ejecución
    ?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname);
        }, 0);
    </script>
    <?php
}
?>

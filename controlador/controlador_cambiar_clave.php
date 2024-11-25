<?php
// Incluye la conexión a la base de datos
include '../modelo/conexion.php';

if (!empty($_POST["btnmodificar"])) {
    if (!empty($_POST["txtclaveactual"]) && !empty($_POST["txtclavenueva"]) && !empty($_POST["txtid"])) {
        $claveActual = md5(trim($_POST["txtclaveactual"]));
        $nuevaClave = md5(trim($_POST["txtclavenueva"]));
        $idUsuario = trim($_POST["txtid"]);

        // Consulta para verificar la contraseña actual del usuario
        $sql = $conexion->prepare("SELECT password FROM usuarios WHERE usuario_id = ?");
        if ($sql) {
            $sql->bind_param("i", $idUsuario);
            $sql->execute();
            $resultado = $sql->get_result();

            if ($resultado->num_rows > 0) {
                $datos = $resultado->fetch_object();

                // Verifica la contraseña actual
                if ($claveActual === $datos->password) {
                    // La contraseña actual es correcta, actualiza con la nueva contraseña
                    $sqlUpdate = $conexion->prepare("UPDATE usuarios SET password = ? WHERE usuario_id = ?");
                    if ($sqlUpdate) {
                        $sqlUpdate->bind_param("si", $nuevaClave, $idUsuario);

                        if ($sqlUpdate->execute()) {
                            // Mensaje de éxito
                            ?>
                            <script>
                                $(function() {
                                    new PNotify({
                                        title: "¡CORRECTO!",
                                        type: "success",
                                        text: "¡Contraseña modificada correctamente!",
                                        styling: "bootstrap3"
                                    });
                                });
                                setTimeout(() => {
                                    window.history.replaceState(null, null, window.location.pathname);
                                }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                            </script>
                            <?php
                        } else {
                            // Mensaje de error al modificar la contraseña
                            ?>
                            <script>
                                $(function() {
                                    new PNotify({
                                        title: "¡ERROR!",
                                        type: "error",
                                        text: "¡Error al modificar la contraseña!",
                                        styling: "bootstrap3"
                                    });
                                });
                                setTimeout(() => {
                                    window.history.replaceState(null, null, window.location.pathname);
                                }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                            </script>
                            <?php
                        }

                        // Cierra la consulta de actualización
                        $sqlUpdate->close();
                    } else {
                        echo "Error preparando la consulta de actualización: " . $conexion->error;
                    }
                } else {
                    // Mensaje de error si la contraseña actual es incorrecta
                    ?>
                    <script>
                        $(function() {
                            new PNotify({
                                title: "¡ERROR!",
                                type: "error",
                                text: "¡La contraseña actual es incorrecta!",
                                styling: "bootstrap3"
                            });
                        });
                        setTimeout(() => {
                            window.history.replaceState(null, null, window.location.pathname);
                        }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                    </script>
                    <?php
                }
            } else {
                // Mensaje de error si no se encuentra el usuario
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡Usuario no encontrado!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.history.replaceState(null, null, window.location.pathname);
                    }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                </script>
                <?php
            }

            // Cierra la consulta de verificación
            $sql->close();
        } else {
            echo "Error preparando la consulta de verificación: " . $conexion->error;
        }
    } else {
        // Mensaje de error si hay campos vacíos
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
            }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
        </script>
        <?php
    }
}
?>

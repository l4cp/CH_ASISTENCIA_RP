<?php
// Incluye la conexión a la base de datos
include "../CH_ASISTENCIA1/modelo/conexion.php";

if (!empty($_POST["btnsalida"])) {
    if (!empty($_POST["txtdni"])) {
        $dni = trim($_POST["txtdni"]);

        // Consulta para verificar si el empleado existe y obtener su nombre
        $consulta = $conexion->prepare("SELECT id_empleado, nombre FROM empleado WHERE dni = ?");
        if ($consulta) {
            $consulta->bind_param("s", $dni);
            $consulta->execute();
            $resultado = $consulta->get_result();

            if ($resultado->num_rows > 0) {
                $datos = $resultado->fetch_object();
                $id_empleado = $datos->id_empleado;
                $nombre_empleado = strtoupper($datos->nombre); // Convertir a mayúsculas

                // Definir la fecha y la hora en el formato deseado
                $fechaHora = date("Y-m-d H:i:s");
                $fechaHoy = date("Y-m-d");

                // Verificar si existe una entrada registrada sin salida para el empleado hoy
                $verificarEntrada = $conexion->prepare("SELECT id_asistencia FROM asistencia WHERE id_empleado = ? AND DATE(entrada) = ? AND salida IS NULL");
                if ($verificarEntrada) {
                    $verificarEntrada->bind_param("is", $id_empleado, $fechaHoy);
                    $verificarEntrada->execute();
                    $resultadoVerificacion = $verificarEntrada->get_result();

                    if ($resultadoVerificacion->num_rows > 0) {
                        // Registrar la salida
                        $sql = $conexion->prepare("UPDATE asistencia SET salida = ? WHERE id_empleado = ? AND DATE(entrada) = ? AND salida IS NULL");
                        if ($sql) {
                            $sql->bind_param("sis", $fechaHora, $id_empleado, $fechaHoy);
                            if ($sql->execute()) {
                                // Mensaje de éxito
                                ?>
                                <script>
                                    $(function() {
                                        new PNotify({
                                            title: "¡CORRECTO!",
                                            type: "success",
                                            text: "¡HASTA MAÑANA, <?= $nombre_empleado ?>!",
                                            styling: "bootstrap3"
                                        });
                                    });
                                    setTimeout(() => {
                                        window.history.replaceState(null, null, window.location.pathname);
                                    }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                                </script>
                                <?php
                            } else {
                                // Mensaje de error al registrar la salida
                                ?>
                                <script>
                                    $(function() {
                                        new PNotify({
                                            title: "¡ERROR!",
                                            type: "error",
                                            text: "¡Error al registrar la salida!",
                                            styling: "bootstrap3"
                                        });
                                    });
                                    setTimeout(() => {
                                        window.history.replaceState(null, null, window.location.pathname);
                                    }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                                </script>
                                <?php
                            }
                            $sql->close();
                        } else {
                            echo "Error preparando la consulta de actualización: " . $conexion->error;
                        }
                    } else {
                        // Mensaje de error si no hay entrada registrada para hoy
                        ?>
                        <script>
                            $(function() {
                                new PNotify({
                                    title: "¡ERROR!",
                                    type: "error",
                                    text: "¡No tienes una entrada registrada hoy!",
                                    styling: "bootstrap3"
                                });
                            });
                            setTimeout(() => {
                                window.history.replaceState(null, null, window.location.pathname);
                            }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                        </script>
                        <?php
                    }
                    $verificarEntrada->close();
                } else {
                    echo "Error preparando la consulta de verificación de entrada: " . $conexion->error;
                }
            } else {
                // Mensaje de error si el DNI no existe en la base de datos
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡El DNI no existe!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.history.replaceState(null, null, window.location.pathname);
                    }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                </script>
                <?php
            }
            $consulta->close();
        } else {
            echo "Error preparando la consulta de verificación: " . $conexion->error;
        }
    } else {
        // Mensaje de error si el campo DNI está vacío
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡El campo DNI está vacío!",
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

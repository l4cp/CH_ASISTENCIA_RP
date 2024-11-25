<?php
include "../modelo/conexion.php"; // Asegúrate de que este archivo contenga la conexión a la base de datos

if (isset($_POST['btnmodificar'])) {
    // Asegúrate de que todos los campos estén presentes
    if (!empty($_POST['txtid']) && !empty($_POST['txttipo_descuento']) && !empty($_POST['txtvalor_descuento'])) {
        // Escapar y validar datos
        $descuento_id = intval($_POST['txtid']);
        $tipo_descuento = htmlspecialchars($_POST['txttipo_descuento']);
        $valor_descuento = htmlspecialchars($_POST['txtvalor_descuento']);
        $motivo_descuento = htmlspecialchars($_POST['txtmotivo_descuento']);
        $fecha_vigencia = htmlspecialchars($_POST['txtfecha_vigencia']);

        // Validar tipo de descuento
        if ($tipo_descuento === 'Porcentaje' || $tipo_descuento === 'Monto Fijo') {
            // Preparar y ejecutar la consulta de actualización
            if ($update_sql = $conexion->prepare("UPDATE Descuentos SET tipo_descuento = ?, valor_descuento = ?, motivo_descuento = ?, fecha_vigencia = ? WHERE descuento_id = ?")) {
                $update_sql->bind_param("sdssi", $tipo_descuento, $valor_descuento, $motivo_descuento, $fecha_vigencia, $descuento_id);

                if ($update_sql->execute()) {
                    ?>
                    <script>
                        $(function() {
                            new PNotify({
                                title: "¡CORRECTO!",
                                type: "success",
                                text: "¡El descuento se ha modificado correctamente!",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
                    <?php
                    // Redirigir a la página de descuentos después de la modificación
                    ?>
                    <script>
                        setTimeout(() => {
                            window.location.href = 'descuentos.php';
                        }, 3000); // Tiempo ajustado para dar tiempo a la notificación
                    </script>
                    <?php
                } else {
                    ?>
                    <script>
                        $(function() {
                            new PNotify({
                                title: "¡ERROR!",
                                type: "error",
                                text: "¡Error al modificar el descuento! <?= $conexion->error ?>",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
                    <?php
                }
                $update_sql->close();
            } else {
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡Error al preparar la consulta: <?= $conexion->error ?>!",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            }
        } else {
            ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡ERROR!",
                        type: "error",
                        text: "¡Tipo de descuento no válido! Debe ser 'Porcentaje' o 'Monto Fijo'.",
                        styling: "bootstrap3"
                    });
                });
            </script>
            <?php
        }
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
        </script>
        <?php
    }
}
?>

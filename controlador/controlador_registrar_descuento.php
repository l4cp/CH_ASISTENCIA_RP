<?php
include "../modelo/conexion.php"; // Asegúrate de que este archivo contenga la conexión a la base de datos

if (!empty($_POST["btnregistrar"])) {
    // Verificar que los campos no estén vacíos
    if (!empty($_POST["txttipo_descuento"]) && !empty($_POST["txtvalor_descuento"]) && !empty($_POST["txtmotivo_descuento"]) && !empty($_POST["txtfecha_vigencia"])) {
        // Escapar los datos para evitar inyecciones SQL
        $tipo_descuento = htmlspecialchars($_POST["txttipo_descuento"]);
        $valor_descuento = htmlspecialchars($_POST["txtvalor_descuento"]);
        $motivo_descuento = htmlspecialchars($_POST["txtmotivo_descuento"]);
        $fecha_vigencia = htmlspecialchars($_POST["txtfecha_vigencia"]);

        // Validar tipo de descuento
        if ($tipo_descuento === 'Porcentaje' || $tipo_descuento === 'Monto Fijo') {
            // Verificar si ya existe un registro con los mismos datos
            $check_stmt = $conexion->prepare("SELECT COUNT(*) FROM Descuentos WHERE tipo_descuento = ? AND valor_descuento = ? AND motivo_descuento = ? AND fecha_vigencia = ?");
            $check_stmt->bind_param("sdss", $tipo_descuento, $valor_descuento, $motivo_descuento, $fecha_vigencia);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 0) {
                echo "<script>
                        $(function() {
                            new PNotify({
                                title: '¡ERROR!',
                                type: 'error',
                                text: '¡El descuento ya está registrado!',
                                styling: 'bootstrap3'
                            });
                        });
                      </script>";
            } else {
                // Preparar y ejecutar la consulta de inserción
                $stmt = $conexion->prepare("INSERT INTO Descuentos (tipo_descuento, valor_descuento, motivo_descuento, fecha_vigencia) VALUES (?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sdss", $tipo_descuento, $valor_descuento, $motivo_descuento, $fecha_vigencia);

                    if ($stmt->execute()) {
                        echo "<script>
                                $(function() {
                                    new PNotify({
                                        title: '¡CORRECTO!',
                                        type: 'success',
                                        text: '¡El descuento se ha registrado correctamente!',
                                        styling: 'bootstrap3'
                                    });
                                });
                              </script>";
                    } else {
                        echo "<script>
                                $(function() {
                                    new PNotify({
                                        title: '¡ERROR!',
                                        type: 'error',
                                        text: '¡Error al registrar el descuento! " . $conexion->error . "',
                                        styling: 'bootstrap3'
                                    });
                                });
                              </script>";
                    }

                    $stmt->close();
                } else {
                    echo "<script>
                            $(function() {
                                new PNotify({
                                    title: '¡ERROR!',
                                    type: 'error',
                                    text: '¡Error al preparar la consulta: " . $conexion->error . "!',
                                    styling: 'bootstrap3'
                                });
                            });
                          </script>";
                }
            }
        } else {
            echo "<script>
                    $(function() {
                        new PNotify({
                            title: '¡ERROR!',
                            type: 'error',
                            text: '¡Tipo de descuento no válido! Debe ser \"Porcentaje\" o \"Monto Fijo\".',
                            styling: 'bootstrap3'
                        });
                    });
                  </script>";
        }
    } else {
        echo "<script>
                $(function() {
                    new PNotify({
                        title: '¡ERROR!',
                        type: 'error',
                        text: '¡Los campos están vacíos!',
                        styling: 'bootstrap3'
                    });
                });
              </script>";
    }

    // Asegurarse de que solo se muestre el mensaje de confirmación o error una vez
    echo "<script>
            setTimeout(() => {
                window.history.replaceState(null, null, window.location.pathname);
            }, 3000);
          </script>";
}
?>

<?php
// Verifica si el parámetro 'id' está presente en la URL
if (!empty($_GET["id"])) {
    // Conexión a la base de datos
    include '../modelo/conexion.php';
    
    // Obtiene el id desde la URL y asegúrate de convertirlo a entero para evitar inyecciones SQL
    $id = (int)$_GET["id"]; 

    // Verificar si el descuento existe antes de eliminarlo
    $check_sql = $conexion->prepare("SELECT 1 FROM Descuentos WHERE descuento_id = ?");
    $check_sql->bind_param("i", $id);
    $check_sql->execute();
    $exists = $check_sql->get_result()->num_rows > 0;

    if ($exists) {
        // Ejecuta la consulta para eliminar el descuento con el id dado
        $sql = $conexion->prepare("DELETE FROM Descuentos WHERE descuento_id = ?");
        if ($sql) {
            $sql->bind_param("i", $id);
            $resultado = $sql->execute();

            // Verifica si la consulta se ejecutó correctamente
            if ($resultado) {
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡CORRECTO!",
                            type: "success",
                            text: "¡Descuento eliminado correctamente!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.location.href = 'descuentos.php'; // Redirige después de la eliminación
                    }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                </script>
                <?php
            } else {
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡Error al eliminar el descuento! <?= $conexion->error ?>",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.location.href = 'descuentos.php'; // Redirige después del error
                    }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
                </script>
                <?php
            }

            // Cierra la consulta
            $sql->close();
        } else {
            echo "Error preparando la consulta de eliminación: " . $conexion->error;
        }
    } else {
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡El descuento no existe!",
                    styling: "bootstrap3"
                });
            });
            setTimeout(() => {
                window.location.href = 'descuentos.php'; // Redirige después del error
            }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
        </script>
        <?php
    }
}
?>

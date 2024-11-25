<?php
include "../modelo/conexion.php"; // Asegúrate de que este archivo contenga la conexión a la base de datos

if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtespecializacion"]) && !empty($_POST["txtcontacto"])) {
        // Escapar los datos para evitar inyecciones SQL
        $nombre = htmlspecialchars($_POST["txtnombre"]);
        $especializacion = htmlspecialchars($_POST["txtespecializacion"]);
        $contacto = htmlspecialchars($_POST["txtcontacto"]);

        // Preparar y ejecutar la consulta de inserción
        if ($stmt = $conexion->prepare("INSERT INTO Docentes (nombre, especializacion, contacto) VALUES (?, ?, ?)")) {
            $stmt->bind_param("sss", $nombre, $especializacion, $contacto);

            if ($stmt->execute()) {
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡CORRECTO!",
                            type: "success",
                            text: "¡El docente se ha registrado correctamente!",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            } else {
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡Error al registrar el docente!",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            }

            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conexion->error;
        }
    } else {
        ?>
        <script>
            $(function() {
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
    ?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname);
        }, 0);
    </script>
<?php
}
?>

<?php
include "../modelo/conexion.php";

if (!empty($_GET["id"])) {
    $id = intval($_GET["id"]); 

    $sql = $conexion->query("delete from asistencia where id_asistencia = $id");

    if ($sql) { ?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "CORRECTO",
                    type: "success",
                    text: "ASISTENCIA ELIMINADA CORRECTAMENTE",
                    styling: "bootstrap3"
                });
            });
        </script>
    <?php } else { ?>
        <script>
            $(function notification() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "ERROR AL ELIMINAR",
                    styling: "bootstrap3"
                });
            });
        </script>
    <?php } ?>

    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname);
        }, 0);
    </script>

<?php
}
?>

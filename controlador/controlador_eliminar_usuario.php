<?php
// Verifica si el parámetro 'id' está presente en la URL
if (!empty($_GET["id"])) {
    // Obtiene el id desde la URL y lo convierte a entero para evitar inyecciones SQL
    $id = (int)$_GET["id"];

    // Ejecuta la consulta para eliminar el usuario con el id dado
    $sql = $conexion->query("DELETE FROM usuario WHERE id_usuario = $id");

    // Verifica si la consulta se ejecutó correctamente
    if ($sql === true) { // Uso de '===' para comparar con booleano
        ?>
        <script>
            $(document).ready(function() {
                new PNotify({
                    title: "CORRECTO",
                    type: "success",
                    text: "Usuario eliminado correctamente",
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
                    title: "INCORRECTO",
                    type: "error",
                    text: "Error al eliminar usuario",
                    styling: "bootstrap3"
                });
            });
        </script>
        <?php
    }
    // Código para reemplazar el estado del historial del navegador
    ?>
    <script>
        setTimeout(() => {
            window.history.replaceState(null, null, window.location.pathname);
        }, 0);
    </script>
    <?php
}
?>

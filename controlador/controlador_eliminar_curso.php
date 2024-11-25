<?php
// Verifica si el parámetro 'id' está presente en la URL
if (!empty($_GET["id"])) {
    // Conexión a la base de datos
    include '../modelo/conexion.php';
    
    // Obtiene el id desde la URL y asegúrate de convertirlo a entero para evitar inyecciones SQL
    $id = (int)$_GET["id"]; 

    // Ejecuta la consulta para eliminar el curso con el id dado
    $sql = $conexion->prepare("DELETE FROM Cursos WHERE curso_id = ?");
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
                        text: "¡Curso eliminado correctamente!",
                        styling: "bootstrap3"
                    });
                });
                setTimeout(() => {
                    window.history.replaceState(null, null, window.location.pathname);
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
                        text: "¡Error al eliminar el curso!",
                        styling: "bootstrap3"
                    });
                });
                setTimeout(() => {
                    window.history.replaceState(null, null, window.location.pathname);
                }, 2000); // Tiempo ajustado para permitir que el usuario lea el mensaje
            </script>
            <?php
        }

        // Cierra la consulta
        $sql->close();
    } else {
        echo "Error preparando la consulta de eliminación: " . $conexion->error;
    }
}
?>

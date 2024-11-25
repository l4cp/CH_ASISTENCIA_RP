<?php
// Incluye la conexión a la base de datos
include '../modelo/conexion.php';

if (!empty($_POST["btnregistrar"])) {
    // Verificar que los campos requeridos no estén vacíos
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtvacantes_max"]) && !empty($_POST["txtcosto"]) && !empty($_POST["txtduracion"]) && !empty($_POST["txtdocente"]) && !empty($_POST["aula_id"])) {

        // Capturar los datos del formulario
        $nombre = trim($_POST["txtnombre"]);
        $vacantes_max = (int)$_POST["txtvacantes_max"];
        $costo = trim($_POST["txtcosto"]);
        $duracion = trim($_POST["txtduracion"]);
        $vacantes_ocup = 0;

        // Capturar el docente y aula seleccionados
        $docente = (int)trim($_POST["txtdocente"]);
        $aula_id = (int)$_POST["aula_id"];

        // Otras variables
        $usuario_id = 1;  // Puedes ajustar esta parte según tu lógica de sesión
        $dias_semana = $_POST["dias_semana"];  // Captura los días seleccionados
        $hora_inicio = $_POST["hora_inicio"];  // Captura las horas de inicio
        $hora_fin = $_POST["hora_fin"];        // Captura las horas de fin

        // Inicia la transacción
        $conexion->begin_transaction();

        try {
            // Verificar si el curso ya existe
            $verificarNombre = $conexion->prepare("SELECT count(*) as total FROM Cursos WHERE nombre_curso = ?");
            $verificarNombre->bind_param("s", $nombre);
            $verificarNombre->execute();
            $resultado = $verificarNombre->get_result();

            if ($resultado->fetch_object()->total > 0) {
                // Mensaje de error si el curso ya existe
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡El curso <?= htmlspecialchars($nombre) ?> ya existe!",
                            styling: "bootstrap3"
                        });
                    });
                    setTimeout(() => {
                        window.history.replaceState(null, null, window.location.pathname);
                    }, 2000);
                </script>
                <?php
            } else {
                // Insertar el curso en la tabla Cursos
                $sql = $conexion->prepare("INSERT INTO Cursos (nombre_curso, usuario_id, docente, duracion, vacantes_ocup, vacantes_max, costo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $sql->bind_param("siissid", $nombre, $usuario_id, $docente, $duracion, $vacantes_ocup, $vacantes_max, $costo);

                if ($sql->execute()) {
                    $curso_id = $sql->insert_id;  // Obtén el ID del curso recién insertado

                    // Insertar los horarios seleccionados por el usuario
                    $sql_horario = $conexion->prepare("INSERT INTO Horarios (curso, aula, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?)");
                    foreach ($dias_semana as $index => $dia) {
                        $hora_ini = $hora_inicio[$index];
                        $hora_fi = $hora_fin[$index];
                        $sql_horario->bind_param("iisss", $curso_id, $aula_id, $dia, $hora_ini, $hora_fi);
                        $sql_horario->execute();  // Ejecuta la inserción de cada horario
                    }

                    // Confirma la transacción
                    $conexion->commit();

                    // Mensaje de éxito
                    ?>
                    <script>
                        $(function() {
                            new PNotify({
                                title: "¡CORRECTO!",
                                type: "success",
                                text: "¡Curso registrado correctamente con horarios!",
                                styling: "bootstrap3"
                            });
                        });
                        setTimeout(() => {
                            window.history.replaceState(null, null, window.location.pathname);
                        }, 2000);
                    </script>
                    <?php
                } else {
                    throw new Exception("Error al registrar el curso");
                }
            }

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conexion->rollback();
            ?>
            <script>
                $(function() {
                    new PNotify({
                        title: "¡ERROR!",
                        type: "error",
                        text: "¡Error: <?= $e->getMessage() ?>!",
                        styling: "bootstrap3"
                    });
                });
                setTimeout(() => {
                    window.history.replaceState(null, null, window.location.pathname);
                }, 2000);
            </script>
            <?php
        }
    } else {
        // Mensaje de error si los campos obligatorios están vacíos
        ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Todos los campos deben ser completados!",
                    styling: "bootstrap3"
                });
            });
            setTimeout(() => {
                window.history.replaceState(null, null, window.location.pathname);
            }, 2000);
        </script>
        <?php
    }
}
?>

<?php
include "../modelo/conexion.php"; 

if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["txtnombre"]) && !empty($_POST["txtapellido"]) && !empty($_POST["txtusuario"]) && !empty($_POST["password"]) && !empty($_POST["txtcelular"])) {
        $nombre = htmlspecialchars($_POST["txtnombre"]);
        $apellido = htmlspecialchars($_POST["txtapellido"]);
        $usuario = htmlspecialchars($_POST["txtusuario"]);
        $password = md5($_POST["password"]);
        $celular = htmlspecialchars($_POST["txtcelular"]);

        // Verificar si el nombre de usuario ya existe
        $stmt = $conexion->prepare("SELECT COUNT(*) AS total FROM Usuarios WHERE usuarme = ?");
        $stmt->bind_param("s", $usuario); 
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result) {
            $row = $result->fetch_object();
            if ($row->total > 0) {
                ?>
                <script>
                    $(function() {
                        new PNotify({
                            title: "¡ERROR!",
                            type: "error",
                            text: "¡El nombre de usuario <?= htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8') ?> ya existe!",
                            styling: "bootstrap3"
                        });
                    });
                </script>
                <?php
            } else {
                // Preparar y ejecutar la inserción de nuevo usuario
                $stmt_insert = $conexion->prepare("INSERT INTO Usuarios (nombre_usuario, apellido_usuario, usuarme, password, celular, estado) VALUES (?, ?, ?, ?, ?, 1)");
                $stmt_insert->bind_param("sssss", $nombre, $apellido, $usuario, $password, $celular);

                if ($stmt_insert->execute()) {
                    ?>
                    <script>
                        $(function() {
                            new PNotify({
                                title: "¡CORRECTO!",
                                type: "success",
                                text: "¡El usuario se ha registrado correctamente!",
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
                                title: "¡INCORRECTO!",
                                type: "error",
                                text: "¡Error al registrar el usuario!",
                                styling: "bootstrap3"
                            });
                        });
                    </script>
                    <?php
                }
                $stmt_insert->close();
            }
        } else {
            echo "Error en la consulta: " . $conexion->error;
        }
        
        $stmt->close();
        $conexion->close();

    } else { ?>
        <script>
            $(function() {
                new PNotify({
                    title: "¡ERROR!",
                    type: "error",
                    text: "¡Todos los campos deben estar completos!",
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

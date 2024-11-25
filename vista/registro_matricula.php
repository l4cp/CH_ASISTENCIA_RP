<?php
session_start();

if (empty($_SESSION['nombre']) || empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}
?>

<style>
  ul li:nth-child(4).activo {
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<!-- inicio del contenido principal -->
<div class="page-content">

  <h4 class="text-center text-secondary">REGISTRO DE MATRÍCULAS</h4>
  <style>
  /* Aquí van los estilos personalizados */
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
  }

  .ui-menu-item {
    padding: 10px 15px;
    font-size: 14px;
    color: #333;
    cursor: pointer;
  }

  .ui-state-active {
    background-color: #007bff;
    color: #fff;
    border-radius: 3px;
  }
</style>
  <?php
  // Incluimos la conexión a la base de datos
  include '../modelo/conexion.php';

  // Controlador para registrar la matrícula
  if (!empty($_POST["btnregistrar"])) {
    if (!empty($_POST["txtestudiante"]) && !empty($_POST["txtcurso"]) && !empty($_POST["txtcosto_matricula"]) && !empty($_POST["txtmonto_final"]) && !empty($_POST["txtfecha_matricula"])) {
        // Capturamos los datos del formulario
        $estudiante = (int)$_POST["txtestudiante"];
        $curso = (int)$_POST["txtcurso"];
        $costo_matricula = (float)$_POST["txtcosto_matricula"];
        $monto_final = (float)$_POST["txtmonto_final"];
        $fecha_matricula = $_POST["txtfecha_matricula"];

        // Insertamos la matrícula en la base de datos
        $sql = $conexion->prepare("INSERT INTO Matriculas (estudiante_id, curso_id, costo_matricula, monto_final, fecha_matricula) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("iidds", $estudiante, $curso, $costo_matricula, $monto_final, $fecha_matricula);

        if ($sql->execute()) {
            echo "<script>alert('¡Matrícula registrada exitosamente!');</script>";
        } else {
            echo "<script>alert('Error al registrar la matrícula');</script>";
        }
    } else {
        echo "<script>alert('Todos los campos son obligatorios');</script>";
    }
  }
  ?>

  <!-- Formulario para registrar nueva matrícula -->
  <div class="row">
    <form action="" method="POST">
      <!-- Estudiante (con autocompletado) -->
      <div class="fl-flex-label mb-4 px-2 col-12">
        <input type="text" id="autocomplete-estudiante" placeholder="Nombre del Estudiante" class="input input__text" required>
        <input type="hidden" name="txtestudiante" id="estudiante-id">
      </div>

      <!-- Curso -->
      <div class="fl-flex-label mb-4 px-2 col-12">
        <select name="txtcurso" class="input input__select" required>
          <option value="">Seleccionar Curso...</option>
          <?php
          $sql = $conexion->query("SELECT * FROM Cursos");
          while ($curso = $sql->fetch_object()) { ?>
            <option value="<?= htmlspecialchars($curso->curso_id, ENT_QUOTES, 'UTF-8') ?>">
              <?= htmlspecialchars($curso->nombre_curso, ENT_QUOTES, 'UTF-8') ?>
            </option>
          <?php } ?>
        </select>
      </div>

      <!-- Costo de Matrícula -->
      <div class="fl-flex-label mb-4 px-2 col-12 ">
        <input type="text" placeholder="Costo de Matrícula" class="input input__text" name="txtcosto_matricula" required>
      </div>

      <!-- Monto Final -->
      <div class="fl-flex-label mb-4 px-2 col-12">
        <input type="text" placeholder="Monto Final" class="input input__text" name="txtmonto_final" required>
      </div>

      <!-- Fecha de Matrícula -->
      <div class="fl-flex-label mb-4 px-2 col-12">
        <input type="date" class="input input__text" name="txtfecha_matricula" id="fecha-matricula" required>
      </div>

      <!-- Botones de acción -->
      <div class="text-right p-2">
        <a href="matriculas.php" class="btn btn-secondary btn-rounded">Atrás</a>
        <button type="submit" value="ok" name="btnregistrar" class="btn btn-primary btn-rounded">Registrar</button>
      </div>
    </form>
  </div>

</div>

<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>

<!-- jQuery UI Autocomplete Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Script para autocompletado -->
<script>
  $(function() {
    $("#autocomplete-estudiante").autocomplete({
      source: function(request, response) {
        $.ajax({
          url: 'buscar_estudiantes.php', // Archivo PHP que manejará la búsqueda
          dataType: 'json',
          data: {
            term: request.term  // El término de búsqueda
          },
          success: function(data) {
            response(data);  // Devuelve los resultados
          }
        });
      },
      minLength: 2,  // Mínimo 2 caracteres para buscar
      select: function(event, ui) {
        $("#estudiante-id").val(ui.item.id);  // Asigna el ID del estudiante al campo oculto
        $("#autocomplete-estudiante").val(ui.item.value);  // Muestra el nombre completo en el campo
        return false;
      }
    });
  });

  // Script para autocompletar la fecha de hoy en el campo de fecha
  window.onload = function() {
    var today = new Date();
    var day = ("0" + today.getDate()).slice(-2); // Obtiene el día y lo formatea con dos dígitos
    var month = ("0" + (today.getMonth() + 1)).slice(-2); // Obtiene el mes (se suma 1 ya que en JS el mes empieza en 0)
    var year = today.getFullYear();
    
    // Formateamos la fecha como "YYYY-MM-DD"
    var formattedDate = year + "-" + month + "-" + day;
    
    // Asignamos la fecha al campo de fecha
    document.getElementById('fecha-matricula').value = formattedDate;
  };
</script>

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

  <h4 class="text-center text-secondary">REGISTRO DE CURSOS</h4>

  <?php
  include '../modelo/conexion.php';
  include "../controlador/controlador_registrar_curso.php";
  ?>

  <div class="row">
    <form action="" method="POST">
      <!-- Nombre del Curso -->
      <div class="fl-flex-label mb-4 px-2 col-12 ">
        <input type="text" placeholder="Nombre del Curso" class="input input__text" name="txtnombre" required>
      </div>

      <!-- Duración del Curso -->
      <div class="fl-flex-label mb-4 px-2 col-12 ">
        <select name="txtduracion" class="input input__select" required>
          <option value="">Seleccionar Duración...</option>
          <option value="1 MES">1 MES</option>
          <option value="3 MESES">3 MESES</option>
          <option value="6 MESES">6 MESES</option>
          <option value="12 MESES">12 MESES</option>
          <option value="18 MESES">18 MESES</option>
        </select>
      </div>
      

      <!-- Aula -->
      <div class="fl-flex-label mb-4 px-2 col-12">
        <select name="aula_id" id="aula-select" class="input input__select" required>
          <option value="">Seleccionar Aula...</option>
          <?php
          // Consulta para obtener las aulas
          $sql = $conexion->query("SELECT * FROM aulas");
          if ($sql) {
            while ($aula = $sql->fetch_object()) { ?>
              <option value="<?= htmlspecialchars($aula->aula_id, ENT_QUOTES, 'UTF-8') ?>" data-aforo="<?= htmlspecialchars($aula->aforo, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($aula->nombre_aula, ENT_QUOTES, 'UTF-8') ?> (Aforo: <?= htmlspecialchars($aula->aforo, ENT_QUOTES, 'UTF-8') ?>)
              </option>
          <?php }
          } else {
            echo "<option value=''>No se encontraron aulas</option>";
          }
          ?>
        </select>
      </div>

      <!-- Vacantes Máximas (automático según el aforo del aula seleccionada) -->
      <div class="fl-flex-label mb-4 px-2 col-12">
        <input type="number" id="vacantes_max" name="txtvacantes_max" placeholder="Vacantes Máximas" class="input input__text" readonly>
      </div>

      <!-- Costo del Curso -->
      <div class="fl-flex-label mb-4 px-2 col-12 ">
        <input type="text" placeholder="Costo" class="input input__text" name="txtcosto" required>
      </div>

      <!-- Docente -->
      <div class="fl-flex-label mb-4 px-2 col-12">
        <select name="txtdocente" class="input input__select" required>
          <option value="">Seleccionar Docente...</option>
          <?php
          // Consulta para obtener los docentes
          $sql = $conexion->query("SELECT * FROM docentes");
          if ($sql) {
            while ($docente = $sql->fetch_object()) { ?>
              <option value="<?= htmlspecialchars($docente->docente_id, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($docente->nombre, ENT_QUOTES, 'UTF-8') ?>
              </option>
          <?php }
          } else {
            echo "<option value=''>No se encontraron docentes</option>";
          }
          ?>
        </select>
      </div>

      <!-- Sección para seleccionar días y horas -->
      <div id="dias-horas">
        <div class="day-hour">
          <div class="fl-flex-label mb-4 px-2 col-12">
            <select name="dias_semana[]" class="input input__select" required>
              <option value="">Seleccionar Día...</option>
              <option value="Lunes">Lunes</option>
              <option value="Martes">Martes</option>
              <option value="Miércoles">Miércoles</option>
              <option value="Jueves">Jueves</option>
              <option value="Viernes">Viernes</option>
              <option value="Sábado">Sábado</option>
            </select>
          </div>
          <div class="mb-4 px-2 col-12 d-flex align-items-center">
            <div class="fl-flex-label mr-2">
              <label for="hora_inicio">Hora Inicio</label>
              <input type="time" class="input input__text" name="hora_inicio[]" required>
            </div>
            <div class="fl-flex-label">
              <label for="hora_fin">Hora Fin</label>
              <input type="time" class="input input__text" name="hora_fin[]" required>
            </div>
          </div>
          <button type="button" class="btn btn-danger remove-day-hour" style="margin-bottom: 10px;">Eliminar</button>
        </div>
      </div>
      <button type="button" id="add-day-hour" class="btn btn-success" style="margin-top: 20px;">Agregar Día y Hora</button>

      <!-- Script para gestionar días y horas dinámicamente -->
      <script>
        document.getElementById('add-day-hour').addEventListener('click', function() {
          var container = document.getElementById('dias-horas');
          var newDayHour = document.createElement('div');
          newDayHour.classList.add('day-hour');
          newDayHour.innerHTML = `
          <div class="fl-flex-label mb-4 px-2 col-12">
            <select name="dias_semana[]" class="input input__select" required>
              <option value="">Seleccionar Día...</option>
              <option value="Lunes">Lunes</option>
              <option value="Martes">Martes</option>
              <option value="Miércoles">Miércoles</option>
              <option value="Jueves">Jueves</option>
              <option value="Viernes">Viernes</option>
              <option value="Sábado">Sábado</option>
            </select>
          </div>
          <div class="mb-4 px-2 col-12 d-flex align-items-center">
            <div class="fl-flex-label mr-2">
              <input type="time" class="input input__text" name="hora_inicio[]" required>
            </div>
            <div class="fl-flex-label">
              <input type="time" class="input input__text" name="hora_fin[]" required>
            </div>
          </div>
          <button type="button" class="btn btn-danger remove-day-hour" style="margin-bottom: 10px;">Eliminar</button>
        `;
          container.appendChild(newDayHour);
        });

        // Eliminar día y hora
        document.getElementById('dias-horas').addEventListener('click', function(e) {
          if (e.target.classList.contains('remove-day-hour')) {
            e.target.parentElement.remove();
          }
        });

        // Actualizar vacantes máximas según el aula seleccionada
        document.getElementById('aula-select').addEventListener('change', function() {
          var aforo = this.options[this.selectedIndex].getAttribute('data-aforo');
          document.getElementById('vacantes_max').value = aforo;
        });
      </script>

      <!-- Campo oculto para enviar el ID del usuario -->


      
      <!-- Botones de acción -->
      <div class="text-right p-2">
        <a href="cursos.php" class="btn btn-secondary btn-rounded">Atrás</a>
        <button type="submit" value="ok" name="btnregistrar" class="btn btn-primary btn-rounded">Registrar</button>
      </div>
    </form>
  </div>

</div>
<!-- fin del contenido principal -->

<!-- por último se carga el footer -->
<?php require('./layout/footer.php'); ?>
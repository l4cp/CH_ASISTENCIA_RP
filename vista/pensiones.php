<?php
session_start();
if (empty($_SESSION['nombre']) || empty($_SESSION['apellido'])) {
    header('location:login/login.php');
    exit();
}
?>

<style>
  ul li:nth-child(2).activo {
    background: rgb(11, 150, 214) !important;
  }
</style>

<!-- primero se carga el topbar -->
<?php require('./layout/topbar.php'); ?>
<!-- luego se carga el sidebar -->
<?php require('./layout/sidebar.php'); ?>

<div class="search-container">
<!-- inicio del contenido principal -->
<div class="page-content">
  <h4 class="text-center text-secondary">LISTA DE ALUMNOS MATRICULADOS</h4>
    <label for="search">Buscar:</label>
    <input type="text" id="search" placeholder="Escriba el nombre del alumno" onkeyup="searchStudent(this.value)">
</div>
<div id="studentList"></div>

<script>
    function searchStudent(query) {
        if (query.length == 0) {
            document.getElementById("studentList").innerHTML = "";
            return;
        }
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "buscar_alumno.php?query=" + query, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("studentList").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>
<div id="pensionDetails"></div>

<script>
    function loadPensions(studentId) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "ver_pensiones.php?estudiante_id=" + studentId, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("pensionDetails").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>
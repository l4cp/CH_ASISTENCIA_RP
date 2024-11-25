<?php
include 'conexion.php';

$estudiante_id = $_GET['estudiante_id'];
$sql = "SELECT Cursos.nombre_curso, Cursos.duración, Cursos.costo AS mensualidad, Matriculas.fecha_matricula
        FROM Matriculas
        INNER JOIN Cursos ON Matriculas.curso_id = Cursos.curso_id
        WHERE Matriculas.estudiante_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $estudiante_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombre_curso = $row['nombre_curso'];
    $duracion = $row['duración'];
    $mensualidad = $row['mensualidad'];
    $fecha_matricula = new DateTime($row['fecha_matricula']);

    echo "<h3>Curso: $nombre_curso</h3>";
    echo "<table><tr><th>Mes</th><th>Monto</th><th>Estado</th></tr>";

    for ($i = 0; $i < $duracion; $i++) {
        $fecha_pago = clone $fecha_matricula;
        $fecha_pago->modify("+$i month");
        $estado = "Pendiente"; // Aquí podrías obtener el estado real desde otra tabla de pagos
        echo "<tr><td>" . $fecha_pago->format('F Y') . "</td><td>S/. " . $mensualidad . "</td><td>" . $estado . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontró información de pensiones para el alumno seleccionado.</p>";
}
?>

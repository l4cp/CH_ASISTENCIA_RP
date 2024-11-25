<?php
include '../modelo/conexion.php';

if (isset($_GET['term'])) {
    $term = $_GET['term'];
    // Ajustamos la consulta SQL para buscar coincidencias en cualquier parte del nombre o apellido
    $sql = $conexion->prepare("SELECT estudiante_id, CONCAT(nombre, ' ', apellido) as nombre_completo FROM Estudiantes WHERE nombre LIKE ? OR apellido LIKE ? LIMIT 10");
    $searchTerm = '%' . $term . '%';  // Agregamos el comodín % antes y después del término de búsqueda
    $sql->bind_param('ss', $searchTerm, $searchTerm);
    $sql->execute();
    $result = $sql->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'id' => $row['estudiante_id'],
            'value' => $row['nombre_completo']
        ];
    }

    echo json_encode($students);
}
?>

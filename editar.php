<?php
$filename = "data.json";
$alumnoParaEditar = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index'])) {
    $index = intval($_GET['index']);
    
    // Cargar el archivo JSON y convertirlo en un array
    $alumnos = json_decode(file_get_contents($filename), true);

    // Obtener el alumno específico basado en el índice
    if ($index >= 0 && $index < count($alumnos)) {
        $alumnoParaEditar = $alumnos[$index];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $index = intval($_POST['index']);
    $alumnos = json_decode(file_get_contents($filename), true);

    // Actualizar el alumno con los datos del formulario
    $alumnos[$index] = [
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'telefono' => $_POST['telefono'],
        'email' => $_POST['email'],
        'nota1' => $_POST['nota1'],
        'nota2' => $_POST['nota2'],
        'nota3' => $_POST['nota3'],
        'asistencia' => $_POST['asistencia'],
        'examenFinal' => $_POST['examenFinal'],
    ];
    
    // Guardar el array actualizado en el archivo JSON
    file_put_contents($filename, json_encode($alumnos));

    // Redireccionar de regreso a la página principal
    header("Location: index.php");
    exit;
}
?>

<!-- Aquí tu código HTML para mostrar el formulario con los datos del alumno para editar -->

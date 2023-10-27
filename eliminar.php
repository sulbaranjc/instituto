<?php
$filename = "data.json";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['index'])) {
    $index = intval($_GET['index']);

    // Cargar el archivo JSON y convertirlo en un array
    $alumnos = json_decode(file_get_contents($filename), true);

    // Verificar si el índice es válido
    if ($index >= 0 && $index < count($alumnos)) {
        // Eliminar el alumno con el índice especificado
        array_splice($alumnos, $index, 1);

        // Guardar el array actualizado en el archivo JSON
        file_put_contents($filename, json_encode($alumnos));
    }
}

// Redireccionar de regreso a la página principal
header("Location: index.php");
exit;
?>

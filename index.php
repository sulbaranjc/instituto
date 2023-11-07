<?php

ini_set('display_errors', 1); // Mostrar errores en pantalla
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); // Reportar todos los errores de PHP



require_once 'saludarTrait.php';// Incluir el trait Saludar
require_once 'persona.php';// Incluir la clase Persona
require_once 'Alumno.php';// Incluir la clase Alumno
require_once 'config.php'; // Incluir la configuración de la base de datos
require_once 'logica.php'; // Incluir la lógica del programa

// Inicializar variables
$isEditing = false; // maneja el estado de edición y agregar
$alumnoToEdit = null; // Almacena el alumno a editar cuando se selecciona la opción de editar
$filter = null; // Almacena el filtro de búsqueda

// inicion de los controladores de las acciones del formulario

if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    $alumnos = filtrarAlumnos($conn, $filter); // Puede que necesites crear esta función.
} else {
    $alumnos = cargarAlumnos($conn); // cargar todos los alumnos
}

// controller para el metodo post del formulario, se recibe el action del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    //echo "entro al post";
    switch ($_POST['action']) {
        case 'add':
            $result = agregarAlumno($_POST, $conn);
            if ($result === true) {
                header("Location: index.php");
            } else {
                echo $result;
            }
            break;
        case 'update':
            if (isset($_POST['id'])) {
                $result = modificarAlumno($_POST, $conn);
                if ($result === true) {
                    //echo "Alumno modificado correctamente";
                    header("Location: index.php");
                } else {
                    echo $result;
                }
            }
            break;
    }
}

// controller para editar un alumno, se recibe el id del alumno a editar
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $isEditing = true; // cambiar el estado de edición
    $idToEdit = intval($_GET['id']); // id del alumno a editar
    $result = editarAlumno($idToEdit, $conn);

    if ($result instanceof Alumno) {
        $alumnoToEdit = $result;
    } else {
        echo $result;
        header("Location: index.php");
    }
}

// controller para eliminar un alumno, se recibe el id del alumno a eliminar
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['index'])) {
    $result = eliminarAlumno(intval($_GET['index']), $conn);
    if ($result === true) {
        header("Location: index.php");
        exit(); // detener la ejecución del script  
    } else {
        echo $result;
    }
}

?>

<!-- formulario y la tabla de listado de alumnos. -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Estilos CSS de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>CRUD Alumnos</title>
</head>
<body>

<!-- Navegación -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">CRUD Alumnos</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Alumnos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Informes</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Ayuda
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
          <li class="text-center"><a href="#" class="enlace-formal" data-bs-toggle="modal" data-bs-target="#acercaDeModal">Acerca de</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- seccion de la vista -->
<div class="container mt-4">
    <h2 class="text-center mb-4"><?= $isEditing ? 'Editar Alumno' : 'Registro de Alumnos' ?></h2> 
<!-- seccion de busqueda -->    
    <form action="index.php" method="get" class="d-flex custom-search-form" role="search">
        <div class="row">    
            <div class="col-md-10">
                <input class="form-control me-2 custom-search-input" type="search" placeholder="Buscar" name="filter" id="filter" aria-label="filter" value="<?= $filter ?>">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-success custom-search-button" type="submit">Buscar</button>
            </div>
        </div>
    </form>
    <!-- Formulario -->
    <form id="formAlumno" onsubmit="return validarNombre()" action="index.php" method="post" class="mb-4">
        <!-- Si estamos editando, incluir un campo oculto con el índice del alumno a editar -->
        <?php if ($isEditing): ?>
            <input type="hidden" name="id" value="<?= $alumnoToEdit->getId() ?>">
            <?php endif; ?>
        <!-- Nombre y Apellido en la misma línea -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $isEditing ? $alumnoToEdit->getNombre() : '' ?>" required>
                    <div id="nombreError" class="form-text text-danger" style="display: none;">El nombre debe tener más de 4 caracteres.</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= $isEditing ? $alumnoToEdit->getApellido() : '' ?>" required>
                </div>
            </div>
        </div>

        <!-- Teléfono y Email en la misma línea -->
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" value="<?= $isEditing ? $alumnoToEdit->getTelefono() : '' ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?= $isEditing ? $alumnoToEdit->getEmail() : '' ?>" required>
                </div>
            </div>
        </div>

        <!-- Notas, asistencia y finales -->
        <div class="row">
            <div class="col">
                <div class="mb-3">
                    <label for="nota1" class="form-label">Nota 1 (20%)</label>
                    <input type="number" class="form-control" id="nota1" name="nota1" step="0.1" min="0" max="10" value="<?= $isEditing ? $alumnoToEdit->getNota1() : '' ?>" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="nota2" class="form-label">Nota 2 (20%)</label>
                    <input type="number" class="form-control" id="nota2" name="nota2" step="0.1" min="0" max="10" value="<?= $isEditing ? $alumnoToEdit->getNota2() : '' ?>" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="nota3" class="form-label">Nota 3 (20%)</label>
                    <input type="number" class="form-control" id="nota3" name="nota3" step="0.1" min="0" max="10" value="<?= $isEditing ? $alumnoToEdit->getNota3() : '' ?>" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="asistencia" class="form-label">Asistencia (10%)</label>
                    <input type="number" class="form-control" id="asistencia" name="asistencia" step="0.1" min="0" max="10" value="<?= $isEditing ? $alumnoToEdit->getAsistencia() : '' ?>" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="examenFinal" class="form-label">Finales (30%)</label>
                    <input type="number" class="form-control" id="examenFinal" name="examenFinal" step="0.1" min="0" max="10" value="<?= $isEditing ? $alumnoToEdit->getExamenFinal() : '' ?>" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="action" value="<?= $isEditing ? 'update' : 'add' ?>">
            <?= $isEditing ? 'Modificar' : 'Agregar' ?>
        </button>
    </form>
</div>
<!-- Tabla de Alumnos -->
<table class="table container">
    <h3 class="text-center">Listado de Alumnos</h3>
    <thead>
        <tr class="text-center">
            <th>Acciones</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Nota Acumulada</th>
            <th>Comentario</th>
            <!-- Otros encabezados de tabla... -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($alumnos as $index => $alumno) : ?>
            <tr>
            <td>
                <!-- Enlaces para Editar, Eliminar y presentarse-->
                <a href="index.php?action=edit&id=<?= $alumno->getId()?>">
                    <img src="imgs/file-edit-line.png" alt="Editar" width="24px">
                </a>
                <a href="index.php?action=delete&index=<?= $alumno->getId() ?>" onclick="return confirm('¿Estás seguro de querer eliminar este registro?')">
                    <img src="imgs/delete-bin-line.png" alt="Eliminar" width="24px">
                </a>
            </td>
            <td><?= $alumno->getNombre() ?></td>
            <td><?= $alumno->getApellido() ?></td>
            <td <?php if($alumno->getNotaAcumulada()<4.5){echo "style='color:red'";}?> class="text-center"><?= $alumno->getNotaAcumulada() ?></td>
            <td <?php if($alumno->getNotaAcumulada()<4.5){echo "style='color:red'";}?> >
                    <?= $alumno->cualitativo($alumno->getNotaAcumulada())?>
                    <!-- <a href="#" class="enlace-formal" data-bs-toggle="modal" data-bs-target="#acercaDeModa2"><img src="imgs/mensaje.png" alt="presentarse" title="" /></a> -->
            </td>
                <!-- Más celdas... -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Acerca de -->
<div class="modal modal-xl fade" id="acercaDeModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Acerca de</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <h3 class="">Curso : Desarrollo web en entorno servidor</h3>
        <h3 class="">Desarrollado por: Juan Carlos Sulbaran</h3>
        <h3 class="">Profesor :  Victor Rodriguez</h3>
        <h3 class="">Practica final primer corte</h3>
        <h3 class="">Version : 0.1</h3>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>

<!-- Modal Acerca Presentarse -->
<!--
<div class="modal modal-xl fade" id="acercaDeModa2" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Acerca de</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <h3 class="">Presentarse</h3>
        <?= $alumno->calificar("te informo que he ",$alumno->getNotaAcumulada())?>
        <h3 class=""></h3>

      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>
-->
<!-- Scripts JS de Bootstrap 5 (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="./scripts.js"></script>
</body>
</html>

<?php
require_once './config.php';
require_once './SaludarTrait.php';
require_once './Persona.php';
require_once './Alumno.php';


/**
 * Conecta a la base de datos carga los datos de los alumnos y los devuelve en un array
 * @return array
 * @param mysqli $conn  Conexión a la base de datos
 */
function cargarAlumnos($conn) {
  $alumnos = [];
  $sql = "SELECT id, nombre, apellido, telefono, correo_electronico as email, nota1, nota2, nota3, asistencia, finales as examenFinal FROM alumno";
  $result = $conn->query($sql);
  
  if ($result && $result->num_rows > 0) {
      // Convertir cada fila en un objeto Alumno
      while($row = $result->fetch_assoc()) {
          $alumnos[] = new Alumno($row['id'],$row['nombre'], $row['apellido'], $row['telefono'], $row['email'], $row['nota1'], $row['nota2'], $row['nota3'], $row['asistencia'], $row['examenFinal']);
      }
      //ordena el array de alumnos por nombre
      usort($alumnos, function($a, $b) {
          return $a->getNombre() <=> $b->getNombre();
      });
  }
  return $alumnos;
}


/**
 * Filtra los alumnos por nombre, apellido, telefono, correo_electronico, nota1, nota2, nota3, asistencia, finales
 */
function filtrarAlumnos($conn, $filter) {
    $alumnos = [];
    $fields = ["nombre", "apellido", "telefono", "correo_electronico", "nota1", "nota2", "nota3", "asistencia", "finales"];
    
    $conditions = [];
    foreach ($fields as $field) {
        $conditions[] = "$field LIKE '%$filter%'";
    }
    $sqlCondition = implode(" OR ", $conditions);

    $sql = "SELECT id, nombre, apellido, telefono, correo_electronico as email, nota1, nota2, nota3, asistencia, finales as examenFinal FROM alumno WHERE $sqlCondition";

    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $alumnos[] = new Alumno($row['id'],$row['nombre'], $row['apellido'], $row['telefono'], $row['email'], $row['nota1'], $row['nota2'], $row['nota3'], $row['asistencia'], $row['examenFinal']);
        }
        
        usort($alumnos, function($a, $b) {
            return $a->getNombre() <=> $b->getNombre();
        });
    }
    return $alumnos;
}

/**
 * Agrega un alumno a la base de datos
 * @param array $data  Datos del alumno a agregar
 * @param mysqli $conn  Conexión a la base de datos
 */
function agregarAlumno($data, $conn) {
  $nombre = $data['nombre'];
  $apellido = $data['apellido'];
  $telefono = $data['telefono'];
  $correo_electronico = $data['correo_electronico'];
  $nota1 = (float) $data['nota1'];
  $nota2 = (float) $data['nota2'];
  $nota3 = (float) $data['nota3'];
  $asistencia = $data['asistencia'];
  $examenFinal = (float) $data['examenFinal'];

  $stmt = $conn->prepare("INSERT INTO alumno (nombre, apellido, telefono, correo_electronico, nota1, nota2, nota3, asistencia, finales) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssddddd", $nombre, $apellido, $telefono, $correo_electronico, $nota1, $nota2, $nota3, $asistencia, $examenFinal);
  
  if($stmt->execute()) {
      return true;
  } else {
      return "Error: " . $stmt->error;
  }
  $stmt->close();
}


/**
 * Modifica un alumno en la base de datos
 * @param array $data  Datos del alumno a modificar
 * @param mysqli $conn  Conexión a la base de datos
 */
function modificarAlumno($data, $conn) {
  $id = intval($data['id']); 
  $nombre = $data['nombre'];
  $apellido = $data['apellido'];
  $telefono = $data['telefono'];
  $correo_electronico = $data['correo_electronico'];
  $nota1 = (float) $data['nota1'];
  $nota2 = (float) $data['nota2'];
  $nota3 = (float) $data['nota3'];
  $asistencia = $data['asistencia'];
  $examenFinal = (float) $data['examenFinal'];

  $stmt = $conn->prepare("UPDATE alumno SET nombre = ?, apellido = ?, telefono = ?, correo_electronico = ?, nota1 = ?, nota2 = ?, nota3 = ?, asistencia = ?, finales = ? WHERE id = ?");
  $stmt->bind_param("ssssdddddi", $nombre, $apellido, $telefono, $correo_electronico, $nota1, $nota2, $nota3, $asistencia, $examenFinal, $id);
  
  if($stmt->execute()) {
      return true;
  } else {
      return "Error: " . $stmt->error;
  }
  $stmt->close();
}


/**
 * Elimina un alumno de la base de datos
 * @param int $id  ID del alumno a eliminar
 * @param mysqli $conn  Conexión a la base de datos
 */
function eliminarAlumno($id, $conn) {
  $stmt = $conn->prepare("DELETE FROM alumno WHERE id = ?");
  $stmt->bind_param("i", $id);
  
  if($stmt->execute()) {
      $stmt->close();
      return true;
  } else {
      $error = "Error al eliminar: " . $stmt->error;
      $stmt->close();
      return $error;
  }
}


/**
 * Elimina un alumno de la base de datos
 * @param int $id  ID del alumno a eliminar
 * @param mysqli $conn  Conexión a la base de datos
 */
function editarAlumno($id, $conn) {
  $stmt = $conn->prepare("SELECT * FROM alumno WHERE id = ?");
  $stmt->bind_param("i", $id);
  
  if($stmt->execute()) {
      $result = $stmt->get_result();
      $stmt->close();

      if ($result && $result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $alumnoToEdit = new Alumno($row['id'], $row['nombre'], $row['apellido'], $row['telefono'], $row['correo_electronico'], $row['nota1'], $row['nota2'], $row['nota3'], $row['asistencia'], $row['finales']);
          return $alumnoToEdit;
      } else {
          return "No se encontró el registro a editar";
      }
  } else {
      $error = "Error al consultar: " . $stmt->error;
      $stmt->close();
      return $error;
  }
}

?>
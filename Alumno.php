<?php

require_once 'Persona.php'; // Importamos la clase padre Persona
require_once 'SaludarTrait.php';  // Importamos el trait

class Alumno extends Persona {
    use Saludar;
    private $nota1;
    private $nota2;
    private $nota3;
    private $asistencia;
    private $examenFinal;

    public function __construct($nombre, $apellido, $telefono, $email, $nota1, $nota2, $nota3, $asistencia, $examenFinal) {
        parent::__construct($nombre, $apellido, $telefono, $email);  // llamamos al constructor de la clase padre
        $this->nota1 = $nota1;
        $this->nota2 = $nota2;
        $this->nota3 = $nota3;
        $this->asistencia = $asistencia;
        $this->examenFinal = $examenFinal;
    }

    // Getters y Setters para nota1
    public function getNota1() {
        return $this->nota1;
    }

    public function setNota1($nota1) {
        $this->nota1 = $nota1;
    }

    // Getters y Setters para nota2
    public function getNota2() {
        return $this->nota2;
    }

    public function setNota2($nota2) {
        $this->nota2 = $nota2;
    }

    // Getters y Setters para nota3
    public function getNota3() {
        return $this->nota3;
    }

    public function setNota3($nota3) {
        $this->nota3 = $nota3;
    }

    // Getters y Setters para asistencia
    public function getAsistencia() {
        return $this->asistencia;
    }

    public function setAsistencia($asistencia) {
        $this->asistencia = $asistencia;
    }

    // Getters y Setters para examenFinal
    public function getExamenFinal() {
        return $this->examenFinal;
    }

    public function setExamenFinal($examenFinal) {
        $this->examenFinal = $examenFinal;
    }

    public function getNotaAcumulada() {
        return $this->nota1 * 0.2 + $this->nota2 * 0.2 + $this->nota3 * 0.2 + $this->asistencia * 0.1 + $this->examenFinal * 0.3;
    }

    public function calificar($mensaje,$notaAcumulada) {
        switch (true) {
            case ($notaAcumulada < 5):
                $mensaje.= "Reprobado y debo repetir el curso";
                break;
    
            case ($notaAcumulada >= 5 && $notaAcumulada <= 7):
                $mensaje .= "Aprobado pero debe esforzarse más";
                break;
    
            case ($notaAcumulada > 7 && $notaAcumulada < 10):
                $mensaje .= "Aprobado y soy Sobresaliente";
                break;
            case ($notaAcumulada >= 10 ):
                    $mensaje .= "Aprobado con honores";
                break;
            }
        
        return $this->saludar($this->nombre, $mensaje); // llamamos al método saludar del trait
    }    
}

?>

<?php

include 'SaludarTrait.php';  // Asegúrate de incluir el archivo que contiene el trait

class Persona {
    use Saludar;  // Usar el trait Saludar
    
    private $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function presentarse($mensaje) {
        return $this->saludar($this->nombre, $mensaje);
    }
}

$persona = new Persona("Juan");
echo $persona->presentarse("¡Es un placer conocerte!");

?>

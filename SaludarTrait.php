<?php

trait Saludar {
    public function saludar($nombre, $mensaje) {
        return "Hola Soy " . $nombre . ", " . $mensaje;
    }
}

?>

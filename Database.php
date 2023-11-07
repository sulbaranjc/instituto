<?php
class Database {
    private static $instance = null;
    private $conn;
    private $servername = "localhost";
    private $username = "sulbaranjc";
    private $password = "4688";
    private $dbname = "instituto";

    private function __construct() { // El constructor es privado, no permite que se genere un objeto desde fuera de la clase
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
    }

    public static function getInstance() {// Singleton
        if (!self::$instance) { // Si no existe la instancia la creamos
            self::$instance = new self(); // new self() es lo mismo que new Database()
        }

        return self::$instance; // Devolvemos la instancia
    }

    public function getConnection() { // Método para obtener la conexión
        return $this->conn;
    }

    // Previene la clonación del objeto
    private function __clone() { } // Clonar es el proceso de crear un objeto duplicado del objeto existente.

    // Previene la deserialización del objeto
    public function __wakeup() { // Deserializar es el proceso de reconstruir el estado de un objeto a partir de una secuencia de bytes.
        throw new Exception("Cannot unserialize a singleton.");
    }
}

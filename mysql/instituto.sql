drop database IF EXISTS instituto;
CREATE DATABASE IF NOT EXISTS instituto;
USE instituto; 

CREATE TABLE alumno (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    correo_electronico VARCHAR(255) NOT NULL,
    nota1 DECIMAL(3, 1) CHECK(nota1 >= 0 AND nota1 <= 10),
    nota2 DECIMAL(3, 1) CHECK(nota2 >= 0 AND nota2 <= 10),
    nota3 DECIMAL(3, 1) CHECK(nota3 >= 0 AND nota3 <= 10),
    asistencia DECIMAL(3, 1) CHECK(asistencia >= 0 AND asistencia <= 10),
    finales DECIMAL(3, 1) CHECK(finales >= 0 AND finales <= 10)
);

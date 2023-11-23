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
INSERT INTO alumno (nombre, apellido, telefono, correo_electronico, nota1, nota2, nota3, asistencia, finales) VALUES 
('Juan', 'Pérez', '123-456-7890', 'juan.perez@email.com', 8.5, 8.8, 9.0, 9.5, 8.7),
('María', 'García', '123-456-7891', 'maria.garcia@email.com', 9.0, 9.2, 8.9, 9.0, 9.0),
('Carlos', 'Rodríguez', '123-456-7892', 'carlos.rodriguez@email.com', 7.8, 8.3, 7.9, 9.3, 8.5),
('Ana', 'Torres', '123-456-7893', 'ana.torres@email.com', 8.9, 9.1, 9.0, 8.8, 8.9),
('Luis', 'Hernández', '123-456-7894', 'luis.hernandez@email.com', 2.0, 1.5, 2.7, 1.0, 3.4);

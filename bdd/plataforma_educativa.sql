-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS plataforma_educativa
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE plataforma_educativa;

-- Crear la tabla usuarios
CREATE TABLE usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

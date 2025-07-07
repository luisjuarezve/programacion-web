-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS plataforma_educativa
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE plataforma_educativa;

-- Crear la tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Crear la tabla progreso_niveles
CREATE TABLE IF NOT EXISTS progreso_niveles (
  id INT(11) NOT NULL AUTO_INCREMENT,
  usuario_id INT(11) NOT NULL,
  nivel_desbloqueado INT(11) NOT NULL DEFAULT 1,
  fecha_actualizacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Crear la tabla respuestas_usuarios
CREATE TABLE IF NOT EXISTS respuestas_usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  usuario_id INT(11) NOT NULL,
  nivel INT(11) NOT NULL,
  pregunta_id INT(11) NOT NULL,
  a INT(11) NOT NULL,
  b INT(11) NOT NULL,
  respuesta TEXT NOT NULL,
  correcta BOOLEAN NOT NULL,
  fecha_respuesta TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
  -- Puedes agregar FOREIGN KEY para pregunta_id si tienes una tabla de preguntas
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Índice único para evitar duplicados y permitir actualizar respuestas
ALTER TABLE respuestas_usuarios
  ADD UNIQUE KEY unique_respuesta (usuario_id, nivel, pregunta_id);

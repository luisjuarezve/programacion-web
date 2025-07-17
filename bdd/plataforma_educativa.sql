CREATE DATABASE IF NOT EXISTS pwgrupo8_plataforma_educativa
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE pwgrupo8_plataforma_educativa;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS respuestas_usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  usuario_id INT(11) NOT NULL,
  pregunta_id INT(11) NOT NULL,
  a INT(11) NOT NULL,
  b INT(11) NOT NULL,
  respuesta TEXT NOT NULL,
  correcta BOOLEAN NOT NULL,
  fecha_respuesta TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  UNIQUE KEY unique_respuesta (usuario_id, pregunta_id)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS recuperaciones_contrasena (
  id INT(11) NOT NULL AUTO_INCREMENT,
  usuario_id INT(11) NOT NULL,
  token VARCHAR(64) NOT NULL,
  expira DATETIME NOT NULL,
  usado BOOLEAN DEFAULT FALSE,
  fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  INDEX idx_token (token)
) ENGINE=InnoDB 
  DEFAULT CHARSET=utf8mb4 
  COLLATE=utf8mb4_general_ci;

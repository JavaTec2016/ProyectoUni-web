CREATE DATABASE IF NOT EXISTS BD_Web_Usuarios;
USE BD_Web_Usuarios;

create table if not exists usuario(
    nombre VARCHAR(100) NOT NULL PRIMARY KEY,
    pass VARCHAR(100) NOT NULL,
    rol VARCHAR (30) NOT NULL
);

DELIMITER //
CREATE TRIGGER usuario_cifrado BEFORE INSERT ON usuario
FOR EACH ROW
BEGIN
    SET NEW.nombre = SHA2(NEW.nombre, 256);
    SET NEW.pass = SHA2(NEW.pass, 256);
END;
//
DELIMITER ;

INSERT INTO usuario VALUES('Ras', 'Acrobacia', 'admin');
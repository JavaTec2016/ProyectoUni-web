CREATE DATABASE IF NOT EXISTS BD_Web_Usuarios;
USE BD_Web_Usuarios;

CREATE ROLE IF NOT EXISTS admin_role;
CREATE ROLE IF NOT EXISTS coordinador_role;
CREATE ROLE IF NOT EXISTS voluntario_role;

GRANT ALL PRIVILEGES ON *.* TO admin_role WITH GRANT OPTION;
GRANT SELECT ON *.* TO coordinador_role;
GRANT SELECT ON *.* TO voluntario_role;
FLUSH PRIVILEGES;

create table if not exists usuario(
    nombre VARCHAR(100) NOT NULL PRIMARY KEY,
    pass VARCHAR(100) NOT NULL,
    rol VARCHAR (30) NOT NULL
);

DELIMITER //
DROP TRIGGER IF EXISTS usuario_cifrado//
CREATE TRIGGER usuario_cifrado BEFORE INSERT ON usuario
FOR EACH ROW
BEGIN
    SET NEW.pass = SHA2(NEW.pass, 256);
END;
//
DELIMITER ;

INSERT INTO usuario VALUES('Ras', 'Acrobacia', 'admin');

DELIMITER //

DROP PROCEDURE IF EXISTS deleteUsuario//
CREATE PROCEDURE deleteUsuario(
    IN nombre VARCHAR(100)
)
BEGIN
    DECLARE usr VARCHAR(200);
    SET usr = nombre;
    START TRANSACTION;
    SET usr = CONCAT('\'', nombre, '\'@\'', '%', '\'');
    SET @sql_cmd = CONCAT('DROP USER IF EXISTS ', usr);

    PREPARE stmt FROM @sql_cmd;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    FLUSH PRIVILEGES;
    COMMIT;
END;
//
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS addUsuario//
CREATE PROCEDURE addUsuario(
    IN nombre VARCHAR(100), 
    IN pass VARCHAR(100),
    IN rol VARCHAR(30)
)
BEGIN
    DECLARE host VARCHAR(50) DEFAULT 'localhost';
    DECLARE user_full VARCHAR(200);
    SET user_full = CONCAT('\'', nombre, '\'@\'', host, '\'');
    
    START TRANSACTION;
    SET @sql_cmd = CONCAT('DROP USER IF EXISTS ', user_full);
    PREPARE stmt FROM @sql_cmd;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    SET @sql_cmd = CONCAT('CREATE USER ', user_full, ' IDENTIFIED WITH mysql_native_password BY \'', pass, '\'');
    PREPARE stmt FROM @sql_cmd;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;

    IF rol = 'admin' THEN
        SET @sql_cmd = CONCAT('GRANT ALL PRIVILEGES ON *.* TO ', user_full, ' WITH GRANT OPTION');
    ELSEIF rol = 'coordinador' THEN
        SET @sql_cmd = CONCAT('GRANT coordinador_role TO ', user_full);
    ELSE
        SET @sql_cmd = CONCAT('GRANT voluntario_role TO ', user_full);
    END IF;
    PREPARE stmt FROM @sql_cmd;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    FLUSH PRIVILEGES;
    COMMIT;
END//

DELIMITER ;

call addUsuario('Ras', 'Acrobacia', 'admin');
call addUsuario('pru', 'pru', 'admin');
call deleteUsuario('pru');
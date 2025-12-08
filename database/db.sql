CREATE DATABASE IF NOT EXISTS BD_Web;
USE BD_Web;

CREATE TABLE IF NOT EXISTS corporacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS anioFiscal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    CHECK (fecha_inicio < fecha_fin)
);

CREATE TABLE IF NOT EXISTS circulo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    monto_minimo DECIMAL(10,2) NOT NULL CHECK (monto_minimo >= 0)
);
CREATE TABLE IF NOT EXISTS donador_categoria(
    nombre VARCHAR(50) NOT NULL PRIMARY KEY
);
INSERT INTO donador_categoria VALUES('Graduado');
INSERT INTO donador_categoria VALUES('Alumno');
INSERT INTO donador_categoria VALUES('Padre');
INSERT INTO donador_categoria VALUES('Administrador');
INSERT INTO donador_categoria VALUES('Personal Docente');
INSERT INTO donador_categoria VALUES('Personal Administrativo');
INSERT INTO donador_categoria VALUES('Corporación');
INSERT INTO donador_categoria VALUES('Amigo');

CREATE TABLE IF NOT EXISTS evento (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    tipo VARCHAR(50),
    descripcion TEXT,
    CHECK(fecha_fin > fecha_inicio)
);

CREATE TABLE IF NOT EXISTS voluntario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    rol VARCHAR(30) NOT NULL CHECK (rol IN ('fonoton','coordinador_clase','otros'))
);

CREATE TABLE IF NOT EXISTS clase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anio_graduacion INT NOT NULL
);

CREATE TABLE IF NOT EXISTS donador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200),
    telefono VARCHAR(10),
    email VARCHAR(100),
    categoria VARCHAR(50) NOT NULL,
    anio_graduacion INT,
    id_clase INT NOT NULL,
    id_corporacion INT,
    nombre_conyuge VARCHAR(100),
    id_corporacion_conyuge INT,
    FOREIGN KEY donadorfk_categoria (categoria) REFERENCES donador_categoria(nombre),
    FOREIGN KEY donadorfk_id_clase (id_clase) REFERENCES clase(id),
    FOREIGN KEY donadorfk_id_corporacion (id_corporacion) REFERENCES corporacion(id),
    FOREIGN KEY donadorfk_id_corporacion_conyuge (id_corporacion_conyuge) REFERENCES corporacion(id)
);

CREATE TABLE IF NOT EXISTS asistenciaEvento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_evento INT NOT NULL,
    id_donador INT NOT NULL,
    FOREIGN KEY (id_evento) REFERENCES Evento(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_donador) REFERENCES Donador(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (id_evento, id_donador)
);
CREATE TABLE IF NOT EXISTS garantia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_donador INT NOT NULL,
    id_evento INT NOT NULL,
    garantia DECIMAL(10,2) NOT NULL,
    pago_total DECIMAL(10,2) DEFAULT 0,
    metodo_pago VARCHAR(50) NOT NULL,
    numero_pagos INT DEFAULT 1,
    numero_tarjeta VARCHAR(20),
    fecha_inicio DATE NOT NULL,
    fecha_garantia DATE NOT NULL,
    id_circulo INT,
    estado VARCHAR(20) DEFAULT 'Pendiente',
    FOREIGN KEY (id_donador) REFERENCES donador(id) ON UPDATE CASCADE,
    FOREIGN KEY (id_circulo) REFERENCES circulo(id) ON UPDATE CASCADE,
    FOREIGN KEY (id_evento) REFERENCES evento(id) ON UPDATE CASCADE,
    CHECK(fecha_garantia > fecha_inicio),
    CHECK (estado IN ('Pendiente', 'Completada')),
    CHECK (numero_pagos > 0),
    CHECK (garantia >= 0),
    CHECK (pago_total >= 0)
);

CREATE TABLE IF NOT EXISTS pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_garantia INT NOT NULL,
    fecha DATE NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_garantia) REFERENCES garantia(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CHECK (monto >= 0)
);

CREATE TABLE IF NOT EXISTS llamada (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_voluntario INT NOT NULL,
    id_donador INT NOT NULL,
    fecha DATE NOT NULL,
    resultado  VARCHAR(50) NOT NULL CHECK (resultado IN ('sin_respuesta','promesa','rechazo','donacion_realizada')),
    observaciones TEXT,
    id_garantia INT NULL,
    FOREIGN KEY (id_voluntario) REFERENCES Voluntario(id),
    FOREIGN KEY (id_donador) REFERENCES Donador(id),
    FOREIGN KEY (id_garantia) REFERENCES Garantia(id)
);



CREATE OR REPLACE VIEW garantia_donador_evento(
    id_garantia,
    id_donador,
    id_evento,
    garantia,
    pago_total,
    metodo_pago,
    numero_pagos,
    numero_tarjeta,
    fecha_inicio,
    fecha_garantia,
    id_circulo,
    estado,

    nombre_donador,
    telefono,
    email,

    nombre_evento
) AS
SELECT 
    g.id,
    g.id_donador,
    g.id_evento,
    g.garantia,
    g.pago_total,
    g.metodo_pago,
    g.numero_pagos,
    g.numero_tarjeta,
    g.fecha_inicio,
    g.fecha_garantia,
    g.id_circulo,
    g.estado,
    d.nombre,
    d.telefono,
    d.email,
    e.nombre
FROM garantia g
INNER JOIN donador d
ON d.id=g.id_donador
INNER JOIN evento e
ON e.id=g.id_evento;


DELIMITER //
DROP FUNCTION IF EXISTS getCirculo//
CREATE FUNCTION getCirculo(monto INT) RETURNS VARCHAR(50) deterministic
BEGIN
    DECLARE circulo VARCHAR(50);

    IF monto >= 50000 THEN
        SET circulo = 'Oro';
    ELSEIF monto >= 100000 THEN
        SET circulo = 'Presidente';
    END IF;

    RETURN circulo;
END//

DELIMITER ;

insert into circulo(nombre, monto_minimo) values ('Presidente', 100000);
insert into garantia values(2, 1, 1, 15000, 0, 'credito', 4, 1234, '2012-12-12', '2026-12-12', 1, 'Pendiente');
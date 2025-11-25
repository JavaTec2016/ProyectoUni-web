CREATE DATABASE IF NOT EXISTS BD_Web;
USE BD_Web;



CREATE TABLE IF NOT EXISTS Corporacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS AnioFiscal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    CHECK (fecha_inicio < fecha_fin)
);

CREATE TABLE IF NOT EXISTS Circulo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    monto_minimo DECIMAL(10,2) NOT NULL CHECK (monto_minimo >= 0)
);

CREATE TABLE IF NOT EXISTS Evento (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE CHECK(fecha_fin == NULL || fecha_fin > fecha_inicio),
    tipo VARCHAR(50),
    descripcion TEXT
);

CREATE TABLE IF NOT EXISTS Voluntario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    rol VARCHAR(30) NOT NULL CHECK (rol IN ('fonoton','coordinador_clase','otros'))
);

CREATE TABLE IF NOT EXISTS Clase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anio_graduacion INT NOT NULL
);



CREATE TABLE IF NOT EXISTS Donador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200),
    telefono VARCHAR(10),
    email VARCHAR(100),
    categoria VARCHAR(50) NOT NULL CHECK (categoria IN ('Graduado', 'Alumno', 'Padre', 'Administrador', 'Personal Docente', 'Personal Administrativo', 'Corporación', 'Amigo')),
    anio_graduacion INT,
    id_clase INT NOT NULL,
    id_corporacion INT,
    nombre_conyuge VARCHAR(100),
    id_corporacion_conyuge INT,
    FOREIGN KEY (id_clase) REFERENCES Clase(id),
    FOREIGN KEY (id_corporacion) REFERENCES Corporacion(id),
    FOREIGN KEY (id_corporacion_conyuge) REFERENCES Corporacion(id)
);

CREATE TABLE IF NOT EXISTS AsistenciaEvento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_evento INT NOT NULL,
    id_donador INT NOT NULL,
    FOREIGN KEY (id_evento) REFERENCES Evento(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_donador) REFERENCES Donador(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (id_evento, id_donador)
);

CREATE TABLE IF NOT EXISTS Garantia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_donador INT NOT NULL,
    id_evento INT NOT NULL,
    garantia DECIMAL(10,2) NOT NULL CHECK (garantia >= 0),
    pago_total DECIMAL(10,2) DEFAULT 0 CHECK (pago_total >= 0),
    metodo_pago VARCHAR(50) NOT NULL,
    numero_pagos INT DEFAULT 1 CHECK (numero_pagos > 0),
    numero_tarjeta VARCHAR(20),
    fecha_inicio DATE NOT NULL,
    fecha_garantia DATE NOT NULL,
    id_circulo INT,
    estado VARCHAR(20) DEFAULT 'Pendiente' CHECK (estado IN ('Pendiente', 'Completada')),
    CHECK(fecha_garantia > fecha_inicio),
    FOREIGN KEY (id_donador) REFERENCES Donador(id) ON UPDATE CASCADE,
    FOREIGN KEY (id_circulo) REFERENCES Circulo(id) ON UPDATE CASCADE,
    FOREIGN KEY (id_evento) REFERENCES Evento(id) ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_garantia INT NOT NULL,
    fecha DATE,
    monto DECIMAL(10,2) NOT NULL CHECK (monto >= 0),
    FOREIGN KEY (id_garantia) REFERENCES Garantia(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Llamada (
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

CREATE OR REPLACE VIEW Garantia_donador_evento(
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
FROM Garantia g
INNER JOIN Donador d
ON d.id=g.id_donador
INNER JOIN Evento e
ON e.id=g.id_evento;
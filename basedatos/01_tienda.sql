-- Crear la base de datos con la codificación y colación adecuadas
CREATE DATABASE IF NOT EXISTS tienda
CHARACTER SET utf8mb4
COLLATE utf8mb4_spanish_ci;

USE tienda;

-- Crear la tabla Roles con codificación y colación utf8mb4
CREATE TABLE IF NOT EXISTS Roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(20) NOT NULL
);

-- Crear la tabla Usuarios con codificación y colación utf8mb4
CREATE TABLE IF NOT EXISTS Usuarios (
    dni VARCHAR(9) PRIMARY KEY,
    nombre_usuario VARCHAR(25) NOT NULL,
    clave VARCHAR(300) NOT NULL,
    direccion VARCHAR(60),
    email VARCHAR(30),
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES Roles(id_rol) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Crear la tabla Productos con codificación y colación utf8mb4
CREATE TABLE IF NOT EXISTS Productos (
    cod_producto CHAR(5) PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL,
    modelo MediumInt NOT NULL,
    descripcion TEXT
);

-- Crear la tabla Proveedores con codificación y colación utf8mb4
CREATE TABLE IF NOT EXISTS Proveedores (
    CIF VARCHAR(9) PRIMARY KEY,
    nombre_proveedor VARCHAR(30) NOT NULL,
    direccion_proveedor VARCHAR(60),
    telefono VARCHAR(20),
    cod_producto CHAR(5) NOT NULL,
    FOREIGN KEY (cod_producto) REFERENCES Productos(cod_producto) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Crear la tabla Compran con codificación y colación utf8mb4
CREATE TABLE IF NOT EXISTS Compran (
    dni VARCHAR(9),
    cod_producto CHAR(5),
    PRIMARY KEY (dni, cod_producto),
    FOREIGN KEY (dni) REFERENCES Usuarios(dni) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (cod_producto) REFERENCES Productos(cod_producto) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Crear la base de datos

CREATE DATABASE IF NOT EXISTS tienda;
USE tienda;

-- Tabla Roles
CREATE TABLE Roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(20) NOT NULL
);

-- Tabla Usuarios
CREATE TABLE Usuarios (
    dni VARCHAR(9) PRIMARY KEY,
    nombre_usuario VARCHAR(25) NOT NULL,
    clave VARCHAR(300) NOT NULL,
    direccion VARCHAR(40),
    email VARCHAR(30),
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES Roles(id_rol) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Tabla Productos
CREATE TABLE Productos (
    cod_producto CHAR(5) PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL,
    modelo MediumInt NOT NULL,
    descripcion TEXT
);

-- Tabla Proveedores
CREATE TABLE Proveedores (
    CIF VARCHAR(9) PRIMARY KEY,
    nombre_proveedor VARCHAR(30) NOT NULL,
    direccion_proveedor VARCHAR(40),
    telefono VARCHAR(20),
    cod_producto CHAR(5) NOT NULL,
    FOREIGN KEY (cod_producto) REFERENCES Productos(cod_producto) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Tabla Compras (Relación muchos a muchos entre Usuarios y Productos)
CREATE TABLE Compran (
    dni VARCHAR(9),
    cod_producto CHAR(5),
    PRIMARY KEY (dni, cod_producto),
    FOREIGN KEY (dni) REFERENCES Usuarios(dni) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (cod_producto) REFERENCES Productos(cod_producto) ON UPDATE CASCADE ON DELETE CASCADE
);
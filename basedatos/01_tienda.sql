
CREATE DATABASE IF NOT EXISTS tienda
CHARACTER SET utf8mb4
COLLATE utf8mb4_spanish_ci;

USE tienda;


CREATE TABLE IF NOT EXISTS Roles (
    id_rol INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(20) NOT NULL
);


CREATE TABLE IF NOT EXISTS Usuarios (
    dni VARCHAR(9) PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    clave VARCHAR(300) NOT NULL,
    direccion VARCHAR(60),
    email VARCHAR(50),
    perfil varchar(550) DEFAULT 'imagenes/fotosPerfil/emoticono.jpg',
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES Roles(id_rol) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS Proveedores (
    CIF VARCHAR(9) PRIMARY KEY,
    nombre_proveedor VARCHAR(30) NOT NULL,
    direccion_proveedor VARCHAR(60),
    telefono VARCHAR(20)
);


CREATE TABLE IF NOT EXISTS Productos (
    cod_producto CHAR(5) PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL,
    modelo MEDIUMINT NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    precio DECIMAL(5,2),
    CIF VARCHAR(9) NOT NULL, 
    FOREIGN KEY (CIF) REFERENCES Proveedores(CIF) ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE IF NOT EXISTS Compran (
    dni VARCHAR(9),
    cod_producto CHAR(5),
    fecha_compra TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cantidad SMALLINT NOT NULL DEFAULT 1,
    subtotal DECIMAL(5,2),
    PRIMARY KEY (dni, cod_producto, fecha_compra),
    FOREIGN KEY (dni) REFERENCES Usuarios(dni) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (cod_producto) REFERENCES Productos(cod_producto) ON UPDATE CASCADE ON DELETE CASCADE
);

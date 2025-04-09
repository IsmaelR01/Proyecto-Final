-- Crear base de datos
USE tienda;
-- Insertar roles (admin, cliente)
INSERT INTO Roles (tipo) VALUES
('admin'),
('cliente');

-- Insertar usuarios (uno admin y dos clientes)
INSERT INTO Usuarios (dni, nombre_usuario, clave, direccion, email, id_rol) VALUES
('12345678A', 'admin_user', 'clave_admin', 'Calle Admin 123', 'admin@modapunto.com', 1),
('23456789B', 'cliente1', 'clave_cliente1', 'Calle Cliente 456', 'cliente1@modapunto.com', 2),
('34567890C', 'cliente2', 'clave_cliente2', 'Calle Cliente 789', 'cliente2@modapunto.com', 2);

-- Insertar productos (jerseys y chaquetas de punto)
INSERT INTO Productos (cod_producto, nombre, modelo, descripcion) VALUES
('J0001', 'Jersey Punto Algodón Rojo', 2023, 'Jersey de punto 100% algodón, color rojo, talla M, cómodo para otoño'),
('J0002', 'Jersey Punto Lana Gris', 2023, 'Jersey de punto 100% lana, color gris, talla L, ideal para invierno'),
('J0003', 'Jersey Punto Cashmere Azul', 2023, 'Jersey de punto 100% cashmere, color azul, talla S, para un look elegante'),
('J0004', 'Jersey Punto Algodón Verde', 2023, 'Jersey de punto 100% algodón, color verde, talla XL, suave y transpirable'),
('J0005', 'Jersey Punto Lana Blanco', 2023, 'Jersey de punto 100% lana, color blanco, talla M, ideal para climas fríos'),
('J0006', 'Jersey Punto Algodón Amarillo', 2023, 'Jersey de punto 100% algodón, color amarillo, talla L, cómodo para primavera'),
('J0007', 'Jersey Punto Sintético Negro', 2023, 'Jersey de punto sintético, color negro, talla M, versátil para cualquier ocasión'),
('J0008', 'Jersey Punto Tejido Jacquard', 2023, 'Jersey de punto jacquard, color rojo y blanco, talla L, diseño exclusivo y moderno'),
('C0001', 'Chaqueta Punto Cuero Negra', 2023, 'Chaqueta de punto con detalles en cuero, color negro, talla S, estilo rocker'),
('C0002', 'Chaqueta Punto Lana Beige', 2023, 'Chaqueta de punto y lana, color beige, talla M, elegante y cálida'),
('C0003', 'Chaqueta Punto Algodón Azul', 2023, 'Chaqueta de punto 100% algodón, color azul, talla L, cómoda para el otoño'),
('C0004', 'Chaqueta Punto Cuero Marrón', 2023, 'Chaqueta de punto con detalles en cuero, color marrón, talla XL, resistente y cómoda'),
('C0005', 'Chaqueta Punto Piel Negra', 2023, 'Chaqueta de punto y piel, color negro, talla M, estilo casual y moderno'),
('C0006', 'Chaqueta Punto Algodón Gris', 2023, 'Chaqueta de punto 100% algodón, color gris, talla L, perfecta para el entretiempo'),
('C0007', 'Chaqueta Punto Piel Marrón', 2023, 'Chaqueta de punto y piel, color marrón, talla S, estilo clásico y duradero');

-- Insertar proveedores
INSERT INTO Proveedores (CIF, nombre_proveedor, direccion_proveedor, telefono, cod_producto) VALUES
('A12345678', 'Proveedor Jerseys Punto', 'Calle Proveedor 1', '912345678', 'J0001'),
('A12345678', 'Proveedor Jerseys Punto', 'Calle Proveedor 1', '912345678', 'J0002'),
('A12345678', 'Proveedor Jerseys Punto', 'Calle Proveedor 1', '912345678', 'J0003'),
('B23456789', 'Proveedor Chaquetas Punto', 'Calle Proveedor 2', '932345678', 'C0001'),
('B23456789', 'Proveedor Chaquetas Punto', 'Calle Proveedor 2', '932345678', 'C0002'),
('B23456789', 'Proveedor Chaquetas Punto', 'Calle Proveedor 2', '932345678', 'C0003'),
('C34567890', 'Proveedor Estilo Punto', 'Calle Proveedor 3', '945678901', 'C0004'),
('C34567890', 'Proveedor Estilo Punto', 'Calle Proveedor 3', '945678901', 'C0005'),
('C34567890', 'Proveedor Estilo Punto', 'Calle Proveedor 3', '945678901', 'C0006'),
('D45678901', 'Proveedor Moda Punto', 'Calle Proveedor 4', '956789012', 'J0004'),
('D45678901', 'Proveedor Moda Punto', 'Calle Proveedor 4', '956789012', 'J0005'),
('D45678901', 'Proveedor Moda Punto', 'Calle Proveedor 4', '956789012', 'J0006'),
('D45678901', 'Proveedor Moda Punto', 'Calle Proveedor 4', '956789012', 'J0007'),
('E56789012', 'Proveedor Punto Elegante', 'Calle Proveedor 5', '967890123', 'J0008');

-- Insertar compras de los usuarios
INSERT INTO Compran (dni, cod_producto) VALUES
('23456789B', 'J0001'), 
('23456789B', 'C0001'), 
('34567890C', 'J0002'),  
('34567890C', 'C0002'),  
('23456789B', 'J0003'),  
('23456789B', 'C0003'),  
('34567890C', 'J0004'),  
('34567890C', 'C0004');  

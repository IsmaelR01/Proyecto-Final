USE tienda;


INSERT INTO Roles (tipo) VALUES
('admin'),
('cliente');


INSERT INTO Usuarios (dni, nombre_usuario, clave, direccion, email, id_rol) VALUES
('12345678Z', 'Admin01', '$2y$12$KQnoDbyzcsg.BdIygtufZexcaWJ9JHdSuTskxVdeRkfFIczAiAnRq', 'Calle Salvador Dalí, 19, 04500 Fiñana (Almería)', 'admin@gmail.com', 1),
('23456789D', 'Antonior73', '$2y$12$q0CX9LmUhxx99/F0f3hB/O/N1qVIXLVCf3fsNmanZRQgTYIFVXsEa', 'Calle Gran Vía, 23, 28013 Madrid', 'antoniorodriguez@gmail.com', 2),
('34567890V', 'Juang34', '$2y$12$eGzBoNIiIczg.4JUUwjPMOdIZTWHvEPqEcrqF3DJvQb.KxS1MKAU.', 'Calle de la Paz, 45, 29008 Málaga', 'juangarcia@gmail.com', 2),
('45678901G', 'Cristiv01', '$2y$12$2uWpwFVsp7MqCog7gV59Ie/vuzGbH1iWcM/O7X8ya95lPtSNFhdO2', 'Calle Mayor, 12, 03001 Alicante', 'cristinavallejo@gmail.com', 2),
('56789012B', 'Martac01', '$2y$12$HNHiW3OpJBUqz0UHa2XB6OwBRb2REleodsl7Zp2.83nQFCt8/lyge', 'Avenida del Puerto, 45, 46001 Valencia', 'martacarvajal@gmail.com', 2),
('67890123B', 'Manolillo01', '$2y$12$wk6dNlwFeL0rPr5LdyaPzOXv38EZu4rA2eH2O6lt/W5E7./8FQWfq', 'Calle Luna, 7, 41001 Sevilla', 'manolomartinez@gmail.com', 2);


INSERT INTO Proveedores (CIF, nombre_proveedor, direccion_proveedor, telefono) VALUES
('A12345678', 'Moda Puntal', 'Calle de las Rosas, 45, 28012 Madrid', '910123456'),
('B23456789', 'Textiles Roza', 'Calle Real, 89, 29012 Málaga', '952234567'),
('C34567890', 'Prendas Elegantes', 'Avenida de los Naranjos, 23, 41004 Sevilla', '955345678'),
('D45678901', 'Confort Punto', 'Calle Cielo, 5, 08012 Barcelona', '933456789'),
('E56789012', 'Estilo Chic', 'Calle del Mar, 11, 02002 Albacete', '967567890'),
('F67890123', 'Fina Ropa', 'Avenida de la Paz, 32, 33005 Oviedo', '984678901'),
('G78901234', 'Lo Más Chic', 'Calle de las Flores, 88, 36002 Pontevedra', '986789012'),
('H89012345', 'Punto Vibes', 'Calle del Sol, 18, 23004 Jaén', '953890123'),
('I90123456', 'Luxe Look', 'Calle Estrella, 9, 29009 Málaga', '952901234'),
('J01234567', 'Moda Unique', 'Calle San Juan, 25, 08023 Barcelona', '939012345');


INSERT INTO Productos (cod_producto, nombre, modelo, descripcion, imagen, precio, CIF) VALUES
('J1000', 'Jersey Punto Algodón Rojo', 2024, 'Jersey de punto confeccionado en algodón 100% orgánico, color rojo intenso. Corte clásico con cuello redondo y puños acanalados. Ideal para entretiempo.', 'imagenes/productos/jersey1.jpg', 24.99,'A12345678'),
('J1001', 'Jersey Punto Lana Gris', 2024, 'Diseño de punto grueso en lana merina, color gris ceniza. Tacto suave y cálido, perfecto para los días más fríos. Talla L, corte recto.', 'imagenes/productos/jersey2.jpg', 27.99,'B23456789'),
('J1002', 'Jersey Punto Cashmere Azul', 2025, 'Elegante jersey de punto fino en cashmere 100% azul medianoche. Tacto lujoso, ideal para ocasiones especiales. Corte entallado, cuello en V.', 'imagenes/productos/jersey3.jpg', 29.99,'C34567890'),
('J1003', 'Jersey Punto Algodón Verde', 2025, 'Jersey ligero en punto de algodón verde oliva. Estilo relajado con acabados en canalé y cuello barco. Ideal para primavera y verano.', 'imagenes/productos/jersey4.jpg', 31.99,'D45678901'),
('J1004', 'Jersey Punto Lana Blanco', 2024, 'Prenda de lana virgen blanca con textura suave. Diseño clásico de invierno con cuello alto y ajuste cómodo. Talla M.', 'imagenes/productos/jersey5.jpg', 34.99,'E56789012'),
('J1005', 'Jersey Punto Algodón Amarillo', 2025, 'Colorido jersey de algodón peinado en tono mostaza. Corte slim fit, ideal para combinar con vaqueros. Talla L.', 'imagenes/productos/jersey6.jpg', 35.99,'F67890123'),
('J1006', 'Jersey Punto Sintético Negro', 2024, 'Jersey versátil de tejido sintético resistente. Color negro con detalles decorativos en los hombros. Perfecto para uso diario.', 'imagenes/productos/jersey7.jpeg', 37.99,'G78901234'),
('J1007', 'Jersey Punto Jacquard', 2024, 'Diseño exclusivo en punto jacquard rojo y blanco con patrones geométricos. Ideal para destacar en invierno. Cuello redondo y ajuste regular.', 'imagenes/productos/jersey8.jpg', 25.99,'H89012345'),
('J1008', 'Jersey Punto Marino', 2025, 'Jersey de mezcla de lana en azul marino profundo. Estilo náutico con botones decorativos en el hombro. Talla L.', 'imagenes/productos/jersey9.jpg', 32.99,'I90123456'),
('J1009', 'Jersey Punto Beige Clásico', 2024, 'Jersey clásico de punto fino color beige arena. Apto para oficina o salidas informales. Tacto suave y transpirable. Talla S.', 'imagenes/productos/jersey10.jpg', 39.99,'J01234567'),
('C1000', 'Chaqueta Punto Cuero Negra', 2024, 'Chaqueta de punto con inserciones de cuero ecológico en hombros y codos. Color negro, estilo urbano. Cierre frontal con cremallera.', 'imagenes/productos/chaqueta1.jpg', 29.99,'A12345678'),
('C1001', 'Chaqueta Punto Lana Beige', 2024, 'Chaqueta cálida de punto en lana natural beige. Bolsillos frontales y botones grandes. Estilo elegante y sobrio. Talla M.', 'imagenes/productos/chaqueta2.jpg', 31.99,'B23456789'),
('C1002', 'Chaqueta Punto Algodón Azul', 2025, 'Prenda casual de algodón azul cielo. Chaqueta ligera ideal para entretiempo. Cierre de botones y cuello en V.', 'imagenes/productos/chaqueta3.jpg', 39.99,'C34567890'),
('C1003', 'Chaqueta Punto Cuero Marrón', 2025, 'Chaqueta marrón oscuro con combinación de punto grueso y parches de cuero auténtico. Talla XL, estilo vintage.', 'imagenes/productos/chaqueta4.jpg', 44.99,'D45678901'),
('C1004', 'Chaqueta Punto Piel Negra', 2024, 'Diseño moderno en mezcla de piel sintética y punto elástico. Chaqueta corta de color negro, ideal para looks urbanos.', 'imagenes/productos/chaqueta5.jpg', 34.99,'E56789012'),
('C1005', 'Chaqueta Punto Algodón Gris', 2024, 'Chaqueta cómoda de algodón gris claro. Detalles en cremallera y cuello alto. Ideal para el entretiempo. Talla L.', 'imagenes/productos/chaqueta6.jpg', 24.99,'F67890123'),
('C1006', 'Chaqueta Punto Piel Marrón', 2025, 'Elegante combinación de piel marrón con paneles de punto. Estilo clásico con acabados de alta calidad. Talla S.', 'imagenes/productos/chaqueta7.jpg', 37.99,'G78901234'),
('C1007', 'Chaqueta Punto Verde Musgo', 2025, 'Chaqueta de punto grueso verde musgo con botones de madera. Estilo montañés, perfecta para otoño. Talla L.', 'imagenes/productos/chaqueta8.jpg', 31.99,'H89012345'),
('C1008', 'Chaqueta Punto Estilo Urbano', 2024, 'Chaqueta de punto con diseño moderno y minimalista. Color negro, con capucha y bolsillos funcionales. Talla M.', 'imagenes/productos/chaqueta9.jpg', 44.99,'I90123456'),
('C1009', 'Chaqueta Punto Corta Beige', 2024, 'Chaqueta de punto corta color beige claro. Estilo juvenil, con cremallera y puños elásticos. Talla XS.', 'imagenes/productos/chaqueta10.jpg', 37.99,'J01234567');



INSERT INTO Compran (dni, cod_producto, fecha_compra , cantidad, subtotal) VALUES
('23456789D', 'J1000', '2025-04-02 13:45:45', 2, 49.98), 
('23456789D', 'C1000', '2025-04-02 13:47:12', 1, 29.99), 
('23456789D', 'J1002', '2025-04-07 17:30:10', 3, 89.97), 
('23456789D', 'C1002', '2025-04-07 17:35:20', 1, 39.99),
('34567890V', 'J1001', '2025-04-14 12:30:45', 2, 55.98),  
('34567890V', 'C1001', '2025-04-14 12:32:30', 1, 31.99),  
('34567890V', 'J1003', '2025-05-01 16:50:55', 4, 127.96),  
('34567890V', 'C1003', '2025-05-01 16:53:36', 2, 89.98);

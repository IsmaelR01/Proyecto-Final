
USE tienda;

INSERT INTO Roles (tipo) VALUES
('admin'),
('cliente');

INSERT INTO Usuarios (dni, nombre_usuario, clave, direccion, email, id_rol) VALUES
('12345678A', 'Admin01', '$2y$12$KQnoDbyzcsg.BdIygtufZexcaWJ9JHdSuTskxVdeRkfFIczAiAnRq', 'Calle Salvador Dalí, 19, 04500 Fiñana, Almería', 'admin@gmail.com', 1),
('23456789B', 'Antonior73', '$2y$12$q0CX9LmUhxx99/F0f3hB/O/N1qVIXLVCf3fsNmanZRQgTYIFVXsEa', 'Calle Gran Vía, 23, 28013 Madrid', 'antoniorodriguez@gmail.com', 2),
('34567890C', 'Juang34', '$2y$12$eGzBoNIiIczg.4JUUwjPMOdIZTWHvEPqEcrqF3DJvQb.KxS1MKAU.', 'Calle de la Paz, 45, 29008 Málaga', 'juangarcia@gmail.com', 2),
('45678901D', 'Cristiv01', '$2y$12$2uWpwFVsp7MqCog7gV59Ie/vuzGbH1iWcM/O7X8ya95lPtSNFhdO2', 'Calle Mayor, 12, 03001 Alicante', 'cristinavallejo@gmail.com', 2),
('56789012E', 'Martac01', '$2y$12$HNHiW3OpJBUqz0UHa2XB6OwBRb2REleodsl7Zp2.83nQFCt8/lyge', 'Avenida del Puerto, 45, 46001 Valencia', 'martacarvajal@gmail.com', 2),
('67890123F', 'Manolillo01', '$2y$12$wk6dNlwFeL0rPr5LdyaPzOXv38EZu4rA2eH2O6lt/W5E7./8FQWfq', 'Calle Luna, 7, 41001 Sevilla', 'manolomartinez@gmail.com', 2);

INSERT INTO Productos (cod_producto, nombre, modelo, descripcion, imagen) VALUES
('J1000', 'Jersey Punto Algodón Rojo', 2024, 'Jersey de punto confeccionado en algodón 100% orgánico, color rojo intenso. Corte clásico con cuello redondo y puños acanalados. Ideal para entretiempo.', 'imagenes/productos/jersey1.jpg'),
('J1001', 'Jersey Punto Lana Gris', 2024, 'Diseño de punto grueso en lana merina, color gris ceniza. Tacto suave y cálido, perfecto para los días más fríos. Talla L, corte recto.','imagenes/productos/jersey2.jpg'),
('J1002', 'Jersey Punto Cashmere Azul', 2025, 'Elegante jersey de punto fino en cashmere 100% azul medianoche. Tacto lujoso, ideal para ocasiones especiales. Corte entallado, cuello en V.', 'imagenes/productos/jersey3.jpg'),
('J1003', 'Jersey Punto Algodón Verde', 2025, 'Jersey ligero en punto de algodón verde oliva. Estilo relajado con acabados en canalé y cuello barco. Ideal para primavera y verano.', 'imagenes/productos/jersey4.jpg'),
('J1004', 'Jersey Punto Lana Blanco', 2024, 'Prenda de lana virgen blanca con textura suave. Diseño clásico de invierno con cuello alto y ajuste cómodo. Talla M.', 'imagenes/productos/jersey5.jpg'),
('J1005', 'Jersey Punto Algodón Amarillo', 2025, 'Colorido jersey de algodón peinado en tono mostaza. Corte slim fit, ideal para combinar con vaqueros. Talla L.', 'imagenes/productos/jersey6.jpg'),
('J1006', 'Jersey Punto Sintético Negro', 2024, 'Jersey versátil de tejido sintético resistente. Color negro con detalles decorativos en los hombros. Perfecto para uso diario.', 'imagenes/productos/jersey7.jpeg'),
('J1007', 'Jersey Punto Jacquard', 2024, 'Diseño exclusivo en punto jacquard rojo y blanco con patrones geométricos. Ideal para destacar en invierno. Cuello redondo y ajuste regular.', 'imagenes/productos/jersey8.jpg'),
('J1008', 'Jersey Punto Marino', 2025, 'Jersey de mezcla de lana en azul marino profundo. Estilo náutico con botones decorativos en el hombro. Talla L.', 'imagenes/productos/jersey9.jpg'),
('J1009', 'Jersey Punto Beige Clásico', 2024, 'Jersey clásico de punto fino color beige arena. Apto para oficina o salidas informales. Tacto suave y transpirable. Talla S.','imagenes/productos/jersey10.jpg'),
('C1000', 'Chaqueta Punto Cuero Negra', 2024, 'Chaqueta de punto con inserciones de cuero ecológico en hombros y codos. Color negro, estilo urbano. Cierre frontal con cremallera.','imagenes/productos/chaqueta1.jpg'),
('C1001', 'Chaqueta Punto Lana Beige', 2024, 'Chaqueta cálida de punto en lana natural beige. Bolsillos frontales y botones grandes. Estilo elegante y sobrio. Talla M.', 'imagenes/productos/chaqueta2.jpg'),
('C1002', 'Chaqueta Punto Algodón Azul', 2025, 'Prenda casual de algodón azul cielo. Chaqueta ligera ideal para entretiempo. Cierre de botones y cuello en V.', 'imagenes/productos/chaqueta3.jpg'),
('C1003', 'Chaqueta Punto Cuero Marrón', 2025, 'Chaqueta marrón oscuro con combinación de punto grueso y parches de cuero auténtico. Talla XL, estilo vintage.', 'imagenes/productos/chaqueta4.jpg'),
('C1004', 'Chaqueta Punto Piel Negra', 2024, 'Diseño moderno en mezcla de piel sintética y punto elástico. Chaqueta corta de color negro, ideal para looks urbanos.', 'imagenes/productos/chaqueta5.jpg'),
('C1005', 'Chaqueta Punto Algodón Gris', 2024, 'Chaqueta cómoda de algodón gris claro. Detalles en cremallera y cuello alto. Ideal para el entretiempo. Talla L.', 'imagenes/productos/chaqueta6.jpg'),
('C1006', 'Chaqueta Punto Piel Marrón', 2025, 'Elegante combinación de piel marrón con paneles de punto. Estilo clásico con acabados de alta calidad. Talla S.', 'imagenes/productos/chaqueta7.jpg'),
('C1007', 'Chaqueta Punto Verde Musgo', 2025, 'Chaqueta de punto grueso verde musgo con botones de madera. Estilo montañés, perfecta para otoño. Talla L.', 'imagenes/productos/chaqueta8.jpg'),
('C1008', 'Chaqueta Punto Estilo Urbano', 2024, 'Chaqueta de punto con diseño moderno y minimalista. Color negro, con capucha y bolsillos funcionales. Talla M.', 'imagenes/productos/chaqueta9.jpg'),
('C1009', 'Chaqueta Punto Corta Beige', 2024, 'Chaqueta de punto corta color beige claro. Estilo juvenil, con cremallera y puños elásticos. Talla XS.', 'imagenes/productos/chaqueta10.jpg');

INSERT INTO Proveedores (CIF, nombre_proveedor, direccion_proveedor, telefono, cod_producto) VALUES
('A12345678', 'Moda Puntal', 'Calle de las Rosas, 45, 28012 Madrid', '910123456', 'J1000'),
('B23456789', 'Textiles Roza', 'Calle Real, 89, 29012 Málaga', '911234567', 'J1001'),
('C34567890', 'Prendas Elegantes', 'Avenida de los Naranjos, 23, 41004 Sevilla', '912345678', 'J1002'),
('D45678901', 'Confort Punto', 'Calle Cielo, 5, 08012 Barcelona', '913456789', 'J1003'),
('E56789012', 'Estilo Chic', 'Calle del Mar, 11, 02002 Albacete', '914567890', 'J1004'),
('F67890123', 'Fina Ropa', 'Avenida de la Paz, 32, 33005 Oviedo', '915678901', 'J1005'),
('G78901234', 'Lo Más Chic', 'Calle de las Flores, 88, 36002 Pontevedra', '916789012', 'J1006'),
('H89012345', 'Punto Vibes', 'Calle del Sol, 18, 23004 Jaén', '917890123', 'J1007'),
('I90123456', 'Luxe Look', 'Calle Estrella, 9, 29009 Málaga', '918901234', 'J1008'),
('J01234567', 'Moda Unique', 'Calle San Juan, 25, 08023 Barcelona', '919012345', 'J1009'),
('K12345678', 'Piel y Punto', 'Avenida Libertad, 14, 28004 Madrid', '920123456', 'C1000'),
('L23456789', 'Cueros y Puntadas', 'Calle Mayor, 6, 28013 Madrid', '921234567', 'C1001'),
('M34567890', 'Estilo Formal', 'Calle de la Reina, 45, 08010 Barcelona', '922345678', 'C1002'),
('N45678901', 'Corte y Punto', 'Calle de la Paz, 12, 35012 Las Palmas', '923456789', 'C1003'),
('O56789012', 'Ropa para Todos', 'Calle del Prado, 50, 14012 Córdoba', '924567890', 'C1004'),
('P67890123', 'Look Perfecto', 'Avenida del Mar, 7, 15008 A Coruña', '925678901', 'C1005'),
('Q78901234', 'Confort Moderno', 'Calle Mayor, 3, 50008 Zaragoza', '926789012', 'C1006'),
('R89012345', 'Punto del Sur', 'Avenida Andalucía, 21, 29005 Málaga', '927890123', 'C1007'),
('S90123456', 'Estilo Puro', 'Calle del Sol, 18, 37008 Salamanca', '928901234', 'C1008'),
('T01234567', 'Punto Trend', 'Calle de la Luna, 24, 29002 Málaga', '929012345', 'C1009');

INSERT INTO Compran (dni, cod_producto) VALUES
('23456789B', 'J1000'), 
('23456789B', 'C1000'), 
('23456789B', 'J1002'), 
('23456789B', 'C1002'),
('34567890C', 'J1001'),  
('34567890C', 'C1001'),  
('34567890C', 'J1003'),  
('34567890C', 'C1003');


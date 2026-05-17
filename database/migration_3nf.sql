-- Migración a Arquitectura Relacional Normalizada (3NF) - Funjupad
-- Autor: JulesBot

-- 1. Catálogo de Colores
CREATE TABLE IF NOT EXISTS `colores` (
  `id_color` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_color` varchar(100) NOT NULL,
  PRIMARY KEY (`id_color`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Catálogo de Ubicaciones
CREATE TABLE IF NOT EXISTS `ubicaciones` (
  `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_ubicacion` varchar(255) NOT NULL,
  PRIMARY KEY (`id_ubicacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Catálogo de Estados Físicos
CREATE TABLE IF NOT EXISTS `estados` (
  `id_estado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(100) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabla de Artículos (Definición del activo)
CREATE TABLE IF NOT EXISTS `articulos` (
  `id_articulo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_articulo` varchar(255) NOT NULL,
  `codigo_interno` varchar(100) DEFAULT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_color` int(11) DEFAULT NULL,
  `product_image` text,
  PRIMARY KEY (`id_articulo`),
  UNIQUE KEY `codigo_interno_unique` (`codigo_interno`),
  KEY `fk_articulos_marcas` (`id_marca`),
  KEY `fk_articulos_categorias` (`id_categoria`),
  KEY `fk_articulos_colores` (`id_color`),
  CONSTRAINT `fk_articulos_marcas` FOREIGN KEY (`id_marca`) REFERENCES `brands` (`brand_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_articulos_categorias` FOREIGN KEY (`id_categoria`) REFERENCES `categories` (`categories_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_articulos_colores` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id_color`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabla de Inventario (Existencias y estados)
CREATE TABLE IF NOT EXISTS `inventario` (
  `id_inventario` int(11) NOT NULL AUTO_INCREMENT,
  `id_articulo` int(11) NOT NULL,
  `id_ubicacion` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '0',
  `rate` varchar(255) DEFAULT NULL,
  `activo` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_inventario`),
  KEY `fk_inventario_articulo` (`id_articulo`),
  KEY `fk_inventario_ubicacion` (`id_ubicacion`),
  KEY `fk_inventario_estado` (`id_estado`),
  CONSTRAINT `fk_inventario_articulo` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id_articulo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_inventario_ubicacion` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id_ubicacion`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_inventario_estado` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Vista SQL para simplificar consultas desde PHP
CREATE OR REPLACE VIEW `vista_inventario` AS
SELECT
    i.id_inventario,
    a.id_articulo,
    a.nombre_articulo,
    a.codigo_interno,
    b.brand_name AS nombre_marca,
    c.categories_name AS nombre_categoria,
    col.nombre_color,
    u.nombre_ubicacion,
    e.nombre_estado,
    i.cantidad,
    i.rate,
    i.activo,
    i.status,
    a.product_image,
    a.id_marca,
    a.id_categoria,
    a.id_color,
    i.id_ubicacion,
    i.id_estado
FROM inventario i
JOIN articulos a ON i.id_articulo = a.id_articulo
LEFT JOIN brands b ON a.id_marca = b.brand_id
LEFT JOIN categories c ON a.id_categoria = c.categories_id
LEFT JOIN colores col ON a.id_color = col.id_color
LEFT JOIN ubicaciones u ON i.id_ubicacion = u.id_ubicacion
LEFT JOIN estados e ON i.id_estado = e.id_estado;

-- Datos de ejemplo iniciales (opcional)
INSERT IGNORE INTO `estados` (`nombre_estado`) VALUES ('Nuevo'), ('Buen Estado'), ('Reparación'), ('Baja');
INSERT IGNORE INTO `colores` (`nombre_color`) VALUES ('Blanco'), ('Negro'), ('Azul'), ('Rojo'), ('Gris');
INSERT IGNORE INTO `ubicaciones` (`nombre_ubicacion`) VALUES ('Almacén Central'), ('Oficina A'), ('Salón de Eventos');

-- CERMOPA Asset Management System Refactoring SQL
-- 1. Modify brands to sedes (logical change only in UI, table stays 'brands')
-- 2. Modify categories to tipos_activo (logical change only in UI, table stays 'categories')

-- 3. Modify product table
ALTER TABLE `product`
ADD COLUMN `codigo_interno` VARCHAR(100) UNIQUE AFTER `product_name`,
ADD COLUMN `color` VARCHAR(50) AFTER `codigo_interno`,
ADD COLUMN `estado` ENUM('Nuevo', 'Buen Estado', 'Reparación', 'Baja') DEFAULT 'Nuevo' AFTER `quantity`,
ADD COLUMN `ubicacion_especifica` VARCHAR(255) AFTER `estado`;

-- 4. Initial Sedes (Brands)
INSERT INTO `brands` (`brand_name`, `brand_active`, `brand_status`) VALUES
('Barinas', 1, 1),
('Guanare', 1, 1),
('Apure', 1, 1);

-- 5. Initial Tipos de Activo (Categories)
INSERT INTO `categories` (`categories_name`, `categories_active`, `categories_status`) VALUES
('Mantelería', 1, 1),
('Mobiliario', 1, 1),
('Equipos', 1, 1);

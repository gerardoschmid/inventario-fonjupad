<?php
/**
 * config/helpers.php
 * Funciones de ayuda global para el sistema Inventario Fonjupad.
 */

/**
 * Función de escape para prevenir Cross-Site Scripting (XSS).
 * Escapa caracteres especiales para su uso seguro en HTML.
 *
 * @param string|null $text El texto a escapar.
 * @return string El texto escapado.
 */
function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

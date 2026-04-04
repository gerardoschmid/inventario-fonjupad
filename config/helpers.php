<?php
/**
 * Utilidades generales del sistema CERMOPA
 */

/**
 * Escapa texto para prevenir ataques XSS.
 *
 * @param string|null $texto Texto a escapar
 * @return string Texto escapado
 */
if (!function_exists('e')) {
    function e($texto) {
        return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Formatea cantidades para visualización.
 *
 * @param int|float $cantidad
 * @return string
 */
if (!function_exists('formatear_numero')) {
    function formatear_numero($cantidad) {
        return number_format($cantidad, 0, ',', '.');
    }
}

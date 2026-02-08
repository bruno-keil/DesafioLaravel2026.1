<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('format_currency')) {
    function format_currency($value)
    {
        if (is_null($value)) {
            return 'R$ 0,00';
        }
        
        return 'R$ ' . number_format((float) $value, 2, ',', '.');
    }
}

if (!function_exists('get_product_photo')) {
    function get_product_photo($product, $index = 0)
    {
        if (!empty($product->foto)) {
            return str_starts_with($product->foto, 'http') 
                ? $product->foto 
                : asset($product->foto);
        }

        $fallbacks = [
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80',
        ];

        return $fallbacks[$index % count($fallbacks)];
    }
}
<?php

if (!function_exists('snakeToCamelArray')) {
    function snakeToCamelArray(array $array): array
    {
        $camel = [];
        foreach ($array as $key => $value) {
            $newKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            $camel[$newKey] = is_array($value) ? snakeToCamelArray($value) : $value;
        }
        return $camel;
    }
}
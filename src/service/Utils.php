<?php

class Utils {
    public static function json_minify(string $json): string {
        return json_encode(json_decode($json, true), JSON_PRETTY_PRINT);
    }
    public static function array_push_assoc(array $array, $key, $value){
        $array[$key] = $value;
        return $array;
    }
}
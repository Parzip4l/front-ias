<?php

if (!function_exists('hid')) {
    function hid($id)
    {
        return \Hashids::encode($id); // cukup begini
    }
}

if (!function_exists('dhid')) {
    function dhid($hash)
    {
        $decoded = \Hashids::decode($hash);
        return $decoded[0] ?? null;
    }
}

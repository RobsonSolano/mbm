<?php

if (!function_exists('form_error')) {
    function form_error($field = '', $prefix = '<div class="text-danger">', $suffix = '</div>') {
        $validation = \Config\Services::validation();
        if ($validation->hasError($field)) {
            return $prefix . $validation->getError($field) . $suffix;
        }
        return '';
    }
}

if (!function_exists('set_value')) {
    function set_value($field, $default = '', $html_escape = true) {
        $value = old($field, $default);
        return $html_escape ? esc($value) : $value;
    }
}
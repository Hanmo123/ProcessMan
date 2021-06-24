<?php
spl_autoload_register(function ($class) {
    if (\strpos($class, 'ProcessMan\\') === 0) {
        $class = \str_replace('\\', \DIRECTORY_SEPARATOR, $class);
        $class = \str_replace('ProcessMan/', '', $class);
        $class = \str_replace('ProcessMan\\', '', $class);
        require_once __DIR__ . '/' . $class . '.php';
    }
});
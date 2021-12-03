<?php

spl_autoload_register(function (string $class) {
    $class = str_ireplace("dlevchik", "src", $class);
    $class = str_ireplace("\\", "/", $class);

    switch ($class) {
        case 'src/Bootstrap':
            require_once __DIR__.'/Bootstrap.php';
        break;
        default:
            require_once __DIR__ . '/' . $class . '.php';
        break;
    }
}, true);

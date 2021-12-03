<?php

spl_autoload_register(static function (string $class) {
    $class = str_ireplace("dlevchik", "src", $class);
    $class = str_ireplace("\\", "/", $class);

    switch ($class) {
        case 'src/Bootstrap':
            require_once __DIR__ . '/Bootstrap.php';
            break;
        case 'Script':
            require_once __DIR__ . '/src/Script.php';
            break;
        default:
            require_once __DIR__ . '/' . $class . '.php';
            break;
    }
});

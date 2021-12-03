<?php

namespace dlevchik;

class Bootstrap
{
    public function handle() {
        //require configs
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/config/routing.php';
        require_once __DIR__ . '/config/services.php';

        \Script::container();
        \Script::routing();
        return 1;
    }
}
<?php

namespace dlevchik;

/**
 * Class Bootstrap is middle point of script execution.
 */
class Bootstrap
{
    public function handle()
    {
        //require configs & definitions
        require_once __DIR__ . '/config/config.php';
        require_once __DIR__ . '/config/services.php';
        require_once __DIR__ . '/src/functions.php';
        require_once __DIR__ . '/config/routing.php';

        return \Script::routing()->getResponse();
    }
}

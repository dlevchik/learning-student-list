<?php

/**
 * @File
 * This file is used for Routing System registrations.
 */

namespace dlevchik\Controller;

\Script::routing()->register('/\/test\/(\d+)/', TestController::class)
    ->inject(function ($container) {
        return new TestController($container->get('routing'));
    });

\Script::routing()->register('/\/two\/(\d+)/', function ($num) {
    echo "two $num";
});

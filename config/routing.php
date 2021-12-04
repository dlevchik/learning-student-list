<?php

/**
 * @File
 * This file is used for Routing System registrations.
 */

namespace dlevchik\Controller;

\Script::routing()->register('/\/test\/(\d+)/', "dlevchik\Controller\TestController::render", "test")
    ->inject(function ($container) {
        return new TestController($container->get('routing'));
    });

<?php

use dlevchik\Service\Container;
use dlevchik\Service\Routing;

class Script
{
    /**
     * Get script DI container.
     *
     * @return \dlevchik\Service\Container
     */
    public static function container(): Container {
        return Container::getInstance();
    }

    /**
     * Get script routing service.
     *
     * @return \dlevchik\Service\Routing
     */
    public static function routing(): Routing {
        return self::container()->get('routing');
    }
}
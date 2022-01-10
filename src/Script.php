<?php

use dlevchik\Framework\Service\Container;
use dlevchik\Framework\Service\Routing;

class Script
{
    /**
     * Get script DI container.
     *
     * @return \dlevchik\Framework\Service\Container
     */
    public static function container(): Container
    {
        return Container::getInstance();
    }

    /**
     * Get script routing service.
     *
     * @return \dlevchik\Framework\Service\Routing
     */
    public static function routing(): Routing
    {
        return self::container()->get('routing');
    }
}

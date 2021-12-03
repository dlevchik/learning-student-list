<?php

namespace dlevchik\Service;

/**
 * Script Routing class responsible for matching uris to Controllers.
 */
class Routing
{
    /**
     * List of Script registered routes.
     * @var array[]
     */
    private array $routes = [];

    public function register($uri, $callable, $name): self {
        return $this;
    }
}
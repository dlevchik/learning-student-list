<?php

namespace dlevchik\Framework\Service;

use dlevchik\Framework\Entity\Route;

/**
 * Script Routing class responsible for matching URIs to Controllers.
 */
class Routing
{
    /**
     * List of Script registered routes.
     * @var Route[]
     */
    private array $routes = [];

    /**
     * Construct new Script Route.
     * @param string $uri_pattern
     * @param string|callable $controller
     * @param string|null $name
     *
     * @return Route
     */
    public function register(string $uri_pattern, $controller, string $name = null): Route
    {
        $new_route = new Route($uri_pattern, $controller, $name);

        if (!$new_route->isAnonymous()) {
            $this->routes[$new_route->getName()] = $new_route;
        } else {
            $this->routes['_anonymous'][] = $new_route;
        }

        return $new_route;
    }

    /**
     * @return \dlevchik\Framework\Entity\Route[]
     */
    public function getList(): array
    {
        return $this->routes;
    }

    /**
     * @param $name
     *
     * @return \dlevchik\Framework\Entity\Route|null
     */
    public function getByName($name): ?Route
    {
        return $this->routes[$name] ?? null;
    }

    /**
     * Match current requested uri to script controller.
     * @return \dlevchik\Framework\Entity\Route|null
     */
    public function findController(): ?Route
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $routes = array_reverse($this->getList());

        $anonymousRoutes = $routes["_anonymous"] ?? [];
        unset($routes["_anonymous"]);

        $arguments = [];
        foreach (array_merge($anonymousRoutes, $routes) as $route) {
            $container = $this->isRouteMatchUri($route, $requestUri, $arguments);
            if (!is_null($container)) {
                return $container;
            }
        }

        return null;
    }

    /**
     * Check given uri to controller and find it's arguments if any.
     * @param $route
     * @param $requestUri
     * @param $arguments
     *
     * @return \dlevchik\Framework\Entity\Route|null
     */
    public function isRouteMatchUri($route, $requestUri, &$arguments): ?Route
    {
        if (1 === preg_match($route->get_uri_pattern(), $requestUri, $arguments)) {
            array_shift($arguments);
            $route->set_arguments($arguments);
            return $route;
        }
        return null;
    }

    /**
     * Get response for script request.
     * @return false|mixed
     */
    public function getResponse()
    {
        $controller = $this->findController();
        if (is_null($controller)) {
            throw new \OutOfBoundsException("Could not found this route", 404);
        }

        return $controller->invoke();
    }
}

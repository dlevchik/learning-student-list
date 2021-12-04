<?php

namespace dlevchik\Service;

use dlevchik\Entity\Route;

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
     * @param string $controller
     * @param string|null $name
     *
     * @return $this
     */
    public function register(string $uri_pattern, string $controller, ?string $name): Route
    {
        $new_route = new Route($uri_pattern, $controller, $name);

        if (!is_null($new_route->getName())) {
            $this->routes[$new_route->getName()] = $new_route;
        } else {
            $this->routes['_anonymous'][] = $new_route;
        }

        return $new_route;
    }

    /**
     * @return \dlevchik\Entity\Route[]
     */
    public function getList(): array
    {
        return $this->routes;
    }

    /**
     * @param $name
     *
     * @return \dlevchik\Entity\Route|null
     */
    public function getByName($name): ?Route
    {
        return $this->routes[$name] ?? null;
    }

    /**
     * Match current requested uri to script controller.
     * @return \dlevchik\Entity\Route|null
     */
    public function findController(): ?Route
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $routes = array_reverse($this->getList());

        $arguments = [];
        foreach ($routes as $route) {
            if (1 === preg_match($route->get_uri_pattern(), $requestUri, $arguments)) {
                array_shift($arguments);
                $route->set_arguments($arguments);
                return $route;
            }
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

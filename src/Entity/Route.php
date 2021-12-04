<?php

namespace dlevchik\Entity;

/**
 * Class Route
 * Used to store and manipulate with controller instance.
 *
 * @see dlevchik\Service\Routing.php
 *
 * @package dlevchik\Entity
 */
class Route
{
    /**
     * Name of the route.
     * @var string
     */
    private string $name;

    /**
     * Controller classname.
     * @var string
     */
    private string $controller_class;

    /**
     * Controller invoked method name.
     * @var string
     */
    private string $controller_method;

    /**
     * Controller Object instance. Undefined till invoke() call.
     * @var mixed
     */
    private $controller;

    /**
     * Regex Pattern to match site uri for controller.
     * Pattern matches array will be provided to controller method as arguments.
     * @var string
     */
    private string $uri_pattern;

    /**
     * Controller method arguments, matches from uri_pattern;
     * @var array
     */
    private array $arguments;

    /**
     * Controller factory function for DI realization.
     * @var callable
     */
    private $injection_callback;

    public function __construct(string $uri_pattern, string $controller, string $name = 'anonymous')
    {
        $controller_parts = explode("::", $controller);
        $controller_class = $controller_parts[0];
        $controller_method = $controller_parts[1] ?? 'handle';

        if (!class_exists($controller_class)) {
            throw new \InvalidArgumentException("No such controller $controller_class");
        }

        if (!method_exists($controller_class, $controller_method)) {
            throw new \InvalidArgumentException("No such method $controller_method in $controller_class");
        }

        $this->uri_pattern = $uri_pattern;
        $this->controller_class = $controller_class;
        $this->controller_method = $controller_method;
        $this->name = $name;
    }

    /**
     * Define controller factory function to provide DI.
     * This function must return controller instance.
     * @param callable $callback
     *
     * @return $this
     */
    public function inject(callable $callback): self
    {
        $this->injection_callback = $callback;
        return $this;
    }

    /**
     * Invoke controller method and return it's result.
     * @return false|mixed
     */
    public function invoke()
    {
        if (isset($this->injection_callback)) {
            $this->controller = call_user_func($this->injection_callback, \Script::container());
        } else {
            $this->controller = new $this->controller_class();
        }

        if (!$this->controller instanceof $this->controller_class) {
            throw new \BadMethodCallException("Could not construct $this->controller_class controller");
        }

        return call_user_func_array([$this->controller, $this->controller_method], $this->arguments);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function get_uri_pattern(): string
    {
        return $this->uri_pattern;
    }

    /**
     * @return array
     */
    public function get_arguments(): ?array
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function set_arguments(array $arguments): self
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return string
     */
    public function get_controller_class(): string
    {
        return $this->controller_class;
    }

    /**
     * @return string
     */
    public function get_controller_method(): string
    {
        return $this->controller_method;
    }

}

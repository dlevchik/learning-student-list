<?php

namespace dlevchik\Framework\Entity;

/**
 * Class Route
 * Used to store and manipulate with controller instance.
 *
 * @see dlevchik\Framework\Service\Routing.php
 *
 * @package dlevchik\Entity
 */
class Route
{
    public const DEFAULT_METHOD = "handle";
    public const DEFAULT_NAME = "undefined";

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
     * Controller Object instance, undefined till invoke() call.
     * OR can be anonymous function.
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

    /**
     * Route constructor.
     *
     * @param string $uri_pattern
     * @param string|callable $controller
     * @param string|null $name
     */
    public function __construct(string $uri_pattern, $controller, ?string $name)
    {
        $this->uri_pattern = $uri_pattern;
        $this->name = $name ?? self::DEFAULT_NAME;

        if (is_callable($controller)) {
            $this->controller = $controller;
            return;
        }

        $controller_parts = explode("::", $controller);
        $controller_class = $controller_parts[0];
        $controller_method = $controller_parts[1] ?? self::DEFAULT_METHOD;

        if (!class_exists($controller_class)) {
            throw new \InvalidArgumentException("No such controller $controller_class");
        }

        if (!method_exists($controller_class, $controller_method)) {
            throw new \InvalidArgumentException(
                "No such method $controller_method in $controller_class"
            );
        }

        $this->controller_class = $controller_class;
        $this->controller_method = $controller_method;
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
        if ($this->isFunction()) {
            throw new \BadMethodCallException("Can't perform DI on anonymous function.");
        }
        $this->injection_callback = $callback;
        return $this;
    }

    /**
     * Invoke controller method and return it's result.
     * @return false|mixed
     */
    public function invoke()
    {
        if ($this->isFunction()) {
            return call_user_func_array($this->controller, $this->arguments);
        }

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

    /**
     * @return bool
     */
    public function isAnonymous(): bool
    {
        return $this->name === self::DEFAULT_NAME;
    }

    /**
     * Check if controller is a simple anonymous function.
     * @return bool
     */
    public function isFunction(): bool
    {
        return is_callable($this->controller);
    }

}

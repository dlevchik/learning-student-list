<?php


namespace dlevchik\Service;

use dlevchik\Traits\SingletonTrait;

/**
 *  Global DI container.
 */
class Container
{
    use SingletonTrait;

    /**
     * Array with all script available services.
     *
     * @var array[]
     */
    private $services = [];

    /**
     * Adds service to container.
     *
     * @param string $service_key
     * @param callable|string $construct_callback
     *
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public function set(string $service_key, $construct_callback): self {
        if (is_callable($construct_callback) || is_string($construct_callback)) {
            $this->services[$service_key]['instance'] = false;
            $this->services[$service_key]['callable'] = $construct_callback;
        } else {
            throw new \InvalidArgumentException('Container set extends $construct_callback to be callable|object|string with service name');
        }

        return $this;
    }

    /**
     * Get service instance, if it doesn't exist create it.
     *
     * @param string $service_key
     *
     * @return mixed
     */
    public function get(string $service_key) {
        if (false !== $this->services[$service_key]['instance']) {
            return $this->services[$service_key]['instance'];
        }

        if (is_callable($this->services[$service_key]['callable'])) {
            $this->services[$service_key]['instance'] = call_user_func($this->services[$service_key]['callable'], $this);
        } else if (is_string($this->services[$service_key]['callable'])) {
            $this->services[$service_key]['instance'] = new $this->services[$service_key]['callable'];
        }

        if (false === $this->services[$service_key]['instance']) {
            throw new \OutOfBoundsException("Container can't find service $service_key");
        }

        return $this->services[$service_key]['instance'];
    }

}
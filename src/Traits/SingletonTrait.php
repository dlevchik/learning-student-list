<?php

namespace dlevchik\Traits;

trait SingletonTrait
{
    /**
     * Store objects instances.
     *
     * @var array
     */
    private static array $instances = [];

    /**
     * Disable "new".
     */
    protected function __construct()
    {
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }
}

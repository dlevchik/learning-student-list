<?php

namespace dlevchik\App\Controller;

class TestController extends BaseController
{
    protected $routing;

    public function __construct($routing)
    {
        $this->routing = $routing;
    }

    public function render($test)
    {
        echo "test $test";
    }

    public function handle($test)
    {
        echo "handle $test";
    }
}

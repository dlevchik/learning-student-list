<?php

/**
 * Script front controller.
 */

use dlevchik\Bootstrap;

require_once dirname(__DIR__) . '/autoload.php';

$bootstrap = new Bootstrap();
$bootstrap->handle();

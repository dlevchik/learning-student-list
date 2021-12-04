<?php

/**
 * @File
 * This file is used to define Script services DI callbacks.
 * Example:
 * @code
 *  \Script::container()->set('service.name', Routing::class);
 * @endcode
 * OR
 * @code
 *  \Script::container()->set('service.name', function ($container) {creation...});
 * @endcode
 */

namespace dlevchik\Service;

\Script::container()->set('routing', Routing::class);

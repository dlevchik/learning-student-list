<?php

/**
 * @File
 * phpcs:ignoreFile
 *
 * This file is used to define Script global variables & settings.
 */

if (getenv('DEV') === "1") {
    ini_set('display_errors', 1);
    ini_set('error_log', 1);
    ini_set('error_reporting', -1);
    define('DEV', true);
}

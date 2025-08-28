<?php
declare(strict_types=1);

if (!function_exists('getShortClassName')) {
    function getShortClassName(string|object $class): string
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        [, $short] = namespaceSplit($class);

        return $short;
    }
}

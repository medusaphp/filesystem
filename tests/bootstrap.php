<?php declare( strict_types = 1);
namespace Medusa\FileSystem\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

// needed to avoid the composer.json autoload-dev property
// because this property could influence the developer experience negatively
// on wrong usage
spl_autoload_register(function(string $className) {
    if (0 !== strpos($className, __NAMESPACE__)) {
        return;
    }

    $relative = str_replace(__NAMESPACE__ . '\\', '', $className);
    $path = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($path)) {
        require_once $path;
    }
});
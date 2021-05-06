<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource\ComposerJson;

/**
 * Class Psr4AutoloadResource
 * @package medusa/filesystem
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
class Psr4AutoloadResource extends AutoloadResource {

    /**
     * Psr4AutoloadResource constructor.
     * @param string $name
     * @param string $location
     */
    public function __construct(string $name, string $location) {
        parent::__construct($name, $location, 'psr-4');
    }
}

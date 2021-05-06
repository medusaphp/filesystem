<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource\ComposerJson;

/**
 * Class AutoloadResource
 * @package medusa/filesystem
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
class AutoloadResource {

    /** @var string */
    private $namespace;

    /** @var string */
    private $location;

    /** @var string */
    private $type;

    /** @var bool */
    private $dev;

    /**
     * AutoloadResource constructor.
     * @param string $namespace
     * @param string $location
     * @param string $type
     */
    public function __construct(?string $namespace, string $location, string $type) {
        $this->namespace = $namespace ?? '';
        $this->location = $location;
        $this->type = $type;
        $this->dev = false;
    }

    /**
     * @return string
     */
    public function getNamespace(): string {
        return $this->namespace;
    }

    /**
     * @return bool
     */
    public function hasNamespace(): bool {
        return $this->namespace !== '';
    }

    /**
     * @return string
     */
    public function getLocation(): string {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isDev(): bool {
        return $this->dev;
    }

    /**
     * @param bool $dev
     * @return AutoloadResource
     */
    public function setDev(bool $dev): AutoloadResource {
        $this->dev = $dev;
        return $this;
    }
}

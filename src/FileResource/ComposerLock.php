<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

use JsonSerializable;
use Medusa\FileSystem\FileResource\ComposerLock\Package;

/**
 * Class ComposerJson
 * @package medusa/filesystem
 * @author Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class ComposerLock extends JsonFile implements JsonSerializable {

    /** @var Package[] */
    private $packages = [];

    /**
     * @param string $filename
     * @return ComposerLock
     */
    public static function create(string $filename): FileInterface {
        return parent::create(rtrim($filename, '/') . '/composer.lock');
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return $this->getData();
    }

    /**
     * @return JsonFileInterface
     */
    public function load(): FileInterface {
        parent::load();

        foreach ($this->getData()['packages'] ?? [] as $package) {
            $package = Package::create($package);
            $this->packages[$package->getName() . '#' . $package->getVersion()] = $package;
        }

        return $this;
    }

    /**
     * @return Package[]
     */
    public function getPackages(): array {
        return $this->packages;
    }
}

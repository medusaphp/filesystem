<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource\ComposerJson;

use JsonSerializable;

/**
 * Class Requirement
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class Requirement implements JsonSerializable {

    /** @var string */
    private $version;

    /** @var string */
    private $packagename;

    /** @var Repository */
    private $source;

    /** @var bool */
    private $dev = false;

    /**
     * Requirement constructor.
     * @param string $packagename
     * @param string $version
     */
    public function __construct(string $packagename, string $version = '*') {
        $this->version = $version;
        $this->packagename = $packagename;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return [
            $this->getPackagename() => $this->getVersion(),
        ];
    }

    /**
     * @return string
     */
    public function getPackagename(): string {
        return $this->packagename;
    }

    /**
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }
    /**
     * Set Version
     * @param string $version
     * @return Requirement
     */
    public function setVersion(string $version): Requirement {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function toString(): string {
        return $this->getPackagename() . ':' . $this->getVersion();
    }

    /**
     * @return bool
     */
    public function isDev(): bool {
        return $this->dev;
    }

    /**
     * Set Dev
     * @param bool $dev
     * @return Requirement
     */
    public function setDev(bool $dev): Requirement {
        $this->dev = $dev;
        return $this;
    }

    /**
     * Get source
     * @return Repository|null
     */
    public function getSource(): ?Repository {
        return $this->source;
    }

    /**
     * @param Repository $repository
     * @return $this
     */
    public function setSource(Repository $repository): Requirement {
        $this->source = $repository;
        return $this;
    }
}

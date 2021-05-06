<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource\ComposerLock;

/**
 * Class Package
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class Package {

    /** @var array */
    private $dist = ['type' => '', 'url' => '', 'shasum' => ''];

    /** @var array */
    private $data;

    /** @var string */
    private $name;

    /** @var string */
    private $version;

    /**
     * @param array $packageData
     * @return Package
     */
    public static function create(array $packageData): Package {
        $self = new self();
        $self->data = $packageData;
        $self->name = $packageData['name'];
        $self->version = (string)$packageData['version'];
        return $self;
    }

    /**
     * @return array
     */
    public function getData(): array {
        $tmp = $this->data;
        $tmp['dist'] = $this->dist;
        return $tmp;
    }

    /**
     * @param string $type
     * @return Package
     */
    public function setDistType(string $type): Package {
        $this->dist['type'] = $type;
        return $this;
    }

    /**
     * @param string $url
     * @return Package
     */
    public function setDistUrl(string $url): Package {
        $this->dist['url'] = $url;
        return $this;
    }

    /**
     * @param string $shasum
     * @return Package
     */
    public function setDistShasum(string $shasum): Package {
        $this->dist['shasum'] = $shasum;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set Name
     * @param string $name
     * @return Package
     */
    public function setName(string $name): Package {
        $this->name = $name;
        return $this;
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
     * @return Package
     */
    public function setVersion(string $version): Package {
        $this->version = $version;
        return $this;
    }
}

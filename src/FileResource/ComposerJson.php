<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

use JsonSerializable;
use Medusa\FileSystem\FileResource\ComposerJson\Author;
use Medusa\FileSystem\FileResource\ComposerJson\AutoloadResource;
use Medusa\FileSystem\FileResource\ComposerJson\Psr4AutoloadResource;
use Medusa\FileSystem\FileResource\ComposerJson\Repository;
use Medusa\FileSystem\FileResource\ComposerJson\Requirement;
use function array_values;

/**
 * Class ComposerJson
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class ComposerJson extends JsonFile implements JsonSerializable {

    /** @var Requirement[] */
    private $requirements = [];

    /** @var Repository[] */
    private $repositories = [];

    /** @var Author[] */
    private $authors = [];

    /** @var AutoloadResource[] */
    private $autoloadResources = [];

    /**
     * @param string $filename
     * @return ComposerJson
     */
    public static function create(string $filename): FileInterface {
        return parent::create(rtrim($filename, '/') . '/composer.json');
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {

        $result = $this->getData();

        $result['repositories'] = array_values($this->repositories);
        $result['require'] = [];
        $result['require-dev'] = [];
        $result['authors'] = $this->authors;
        $result['autoload'] = [];
        $result['autoload-dev'] = [];

        foreach ($this->requirements as $requirement) {

            if ($requirement->isDev()) {
                $result['require-dev'][$requirement->getPackagename()] = $requirement->getVersion();
            } else {
                $result['require'][$requirement->getPackagename()] = $requirement->getVersion();
            }
        }

        /** @var AutoloadResource $autoloadResource */
        foreach ($this->autoloadResources as $autoloadResource) {
            if ($autoloadResource->isDev()) {
                $resultPtr = &$result['autoload-dev'];
            } else {
                $resultPtr = &$result['autoload'];
            }

            $resultPtr[$autoloadResource->getType()] ??= [];
            $resultPtr = &$resultPtr[$autoloadResource->getType()];

            if ($autoloadResource->hasNamespace()) {
                $resultPtr[$autoloadResource->getNamespace()] = $autoloadResource->getLocation();
            } else {
                $resultPtr[] = $autoloadResource->getLocation();
            }
        }

        return array_filter($result);
    }

    /**
     * @return JsonFileInterface
     */
    public function load(): FileInterface {
        parent::load();

        foreach ($this->getData()['require'] ?? [] as $package => $version) {
            $requirement = new Requirement($package, (string)$version);
            $this->requirements[$requirement->getPackagename()] = $requirement;
        }

        foreach ($this->getData()['require-dev'] ?? [] as $package => $version) {
            $requirement = new Requirement($package, (string)$version);
            $requirement->setDev(true);
            $this->requirements[$requirement->getPackagename()] = $requirement;
        }

        foreach ($this->getData()['repositories'] ?? [] as $repositoryData) {
            $repository = new Repository($repositoryData['url'], $repositoryData['type']);
            $this->repositories[$repository->toString()] = $repository;
        }

        foreach ($this->getData()['authors'] ?? [] as $author) {
            $authorInstance = new Author($author['name'], $author['email']);
            $this->authors[] = $authorInstance;
        }

        $this->loadAutoloadResources($this->getData()['autoload'] ?? [], false);
        $this->loadAutoloadResources($this->getData()['autoload-dev'] ?? [], true);

        return $this;
    }

    /**
     * @param array $autoloads
     * @param bool  $isDev
     * @return void
     */
    private function loadAutoloadResources(array $autoloads, bool $isDev): void {
         /** @var string $type */
         /** @var array $resources */
        foreach ($autoloads as $type => $resources) {
            /** @var array $resource */
            foreach ($resources as $namespace => $location) {
                if ($type === 'psr-4') {
                    $resource = new Psr4AutoloadResource($namespace, $location);
                } else {
                    $namespace = is_string($namespace) ? $namespace : null;
                    $resource = new AutoloadResource($namespace, $location, $type);
                }

                $resource->setDev($isDev);

                $this->autoloadResources[] = $resource;
            }
        }
    }

    /**
     * @param string $packagename
     * @return Requirement
     * @csIgnoreParamCount because it gets something from an internal collection and not from the object itself
     */
    public function getRequirement(string $packagename): Requirement {
        return $this->requirements[$packagename];
    }

    /**
     * Add requirement
     * @param Requirement $requirement
     * @return ComposerJson
     */
    public function addRequirement(Requirement $requirement): ComposerJson {

        $this->requirements[$requirement->getPackagename()] = $requirement;

        $source = $requirement->getSource();

        if ($source) {
            $this->addRepository($source);
        }

        return $this;
    }

    /**
     * @param Repository $repository
     * @return ComposerJson
     */
    public function addRepository(Repository $repository): ComposerJson {
        $this->repositories[$repository->toString()] = $repository;
        return $this;
    }

    /**
     * @param string $name
     * @return ComposerJson
     */
    public function setName(string $name): ComposerJson {
        $jsonData = $this->getData();
        $jsonData['name'] = $name;
        $this->setData($jsonData);
        return $this;
    }

    /**
     * @param string $type
     * @return ComposerJson
     */
    public function setType(string $type): ComposerJson {
        $jsonData = $this->getData();
        $jsonData['type'] = $type;
        $this->setData($jsonData);
        return $this;
    }

    /**
     * @param string $description
     * @return ComposerJson
     */
    public function setDescription(string $description): ComposerJson {
        $jsonData = $this->getData();
        $jsonData['description'] = $description;
        $this->setData($jsonData);
        return $this;
    }

    /**
     * @param Author $author
     * @return ComposerJson
     */
    public function addAuthor(Author $author): ComposerJson {
        $this->authors[] = $author;
        return $this;
    }

    /**
     * @param ComposerJson\AutoloadResource $resource
     * @return ComposerJson
     */
    public function addAutoloadResource(ComposerJson\AutoloadResource $resource): ComposerJson {
        $this->autoloadResources[] = $resource;
        return $this;
    }

    /**
     * @return array|AutoloadResource[]
     */
    public function getAutoloadResources(): array {
        return $this->autoloadResources;
    }
}

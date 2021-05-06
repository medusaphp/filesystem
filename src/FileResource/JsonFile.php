<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

/**
 * Class JsonFile
 * @package medusa/filesystem
 * @author Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class JsonFile extends FileAbstract implements JsonFileInterface {

    /** @var array */
    private $data = [];

    /**
     * @param FileInterface $file
     * @return JsonFile
     */
    public static function fromFile(FileInterface $file): JsonFile {
        $instance = self::create($file->getLocation());
        $instance->setContent($file->getContent());
        return $instance;
    }

    /**
     * Load
     * @return JsonFile
     */
    public function load(): FileInterface {
        $this->data = json_decode(file_get_contents($this->getFilename()), true);
        return $this;
    }

    /**
     * Get file content
     * @return string
     */
    public function getContent(): string {
        return $this->toJson();
    }

    /**
     * Convert data to pretty json
     * @return string
     */
    public function toJson(): string {
        return str_replace(
            '    ',
            '  ',
            json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * @param string $content
     * @return FileInterface
     */
    public function setContent(string $content): FileInterface {
        $this->data = json_decode($content, true);
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return $this->getData();
    }

    /**
     * @return array
     */
    public function getData(): array {
        return $this->data ?? [];
    }

    /**
     * Set Data
     * @param array $data
     * @return JsonFileInterface
     */
    public function setData(array $data): JsonFileInterface {
        $this->data = $data;
        return $this;
    }
}

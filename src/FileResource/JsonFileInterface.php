<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

use JsonSerializable;

/**
 * Class JsonFile
 * @package medusa/filesystem
 * @author Pascal Schnell <pascal.schnell@getmedusa.org>
 */
interface JsonFileInterface extends FileInterface, JsonSerializable {

    /**
     * @return string
     */
    public function toJson(): string;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * Set Data
     * @param array $data
     * @return JsonFileInterface
     */
    public function setData(array $data): JsonFileInterface;
}

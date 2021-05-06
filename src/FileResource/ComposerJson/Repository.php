<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource\ComposerJson;

use JsonSerializable;

/**
 * Class Repository
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class Repository implements JsonSerializable {

    /** @var string */
    private $source;

    /** @var string */
    private $type;

    /**
     * Repository constructor.
     * @param string $source
     * @param string $type
     */
    public function __construct(string $source, string $type) {
        $this->source = $source;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return [
            'url'  => $this->getSource(),
            'type' => $this->getType(),
        ];
    }

    /**
     * @return string
     */
    public function getSource(): string {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @return string
     */
    public function toString(): string {
        return $this->getSource() . '-' . $this->getType();
    }
}

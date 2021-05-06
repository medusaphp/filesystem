<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource\ComposerJson;

use JsonSerializable;

/**
 * Class Author
 * @package medusa/filesystem
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
class Author implements JsonSerializable {

    /** @var string */
    private $name;

    /** @var string */
    private $email;

    /**
     * Author constructor.
     * @param string $name
     * @param string $email
     */
    public function __construct(string $name, string $email) {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}

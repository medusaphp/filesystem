<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

use function file_get_contents;

/**
 * Class File
 * @package medusa/filesystem
 * @author  Pascal Schnell <pascal.schnell@getmedusa.org>
 */
class File extends FileAbstract implements FileInterface {

    /** @var string */
    private $content = '';

    /**
     * Get file contents
     * @return FileInterface
     */
    public function load(): FileInterface {
        $this->content = file_get_contents($this->getFilename());
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * Set content
     * @param string $content
     * @return FileInterface
     */
    public function setContent(string $content): FileInterface {
        $this->content = $content;
        return $this;
    }
}

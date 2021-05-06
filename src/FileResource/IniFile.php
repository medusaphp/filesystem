<?php declare(strict_types = 1);
namespace Medusa\FileSystem\FileResource;

use function array_keys;
use function count;
use function file_get_contents;
use function is_array;
use function is_bool;
use function ksort;
use function parse_ini_string;
use function range;
use function rtrim;
use const PHP_EOL;

/**
 * Class IniFile
 * @package medusa/filesystem
 * @author  Anton Zoffmann <anton.zoffmann@getmedusa.org>
 */
class IniFile extends FileAbstract implements FileInterface {

    /** @var array */
    private $data = [];

    /** @var bool */
    private $sections;

    /** @var int */
    private int $scannerMode = INI_SCANNER_RAW;
  
    /**
     * IniFile constructor.
     * @param string $filename
     * @param bool   $sections
     */
    public function __construct(string $filename, bool $sections = false) {
        parent::__construct($filename);
        $this->sections = $sections;
    }

    /**
     * @param int $scannerMode
     * @return IniFile
     */
    public function setScannerMode(int $scannerMode): IniFile {
        $this->scannerMode = $scannerMode;
        return $this;
    }

    /**
     * @param FileInterface $file
     * @return IniFile
     */
    public static function fromFile(FileInterface $file): IniFile {
        $instance = self::create($file->getLocation());
        $instance->setContent($file->getContent());

        return $instance;
    }

    /**
     * @return FileInterface
     */
    public function load(): FileInterface {
        $this->setContent(file_get_contents($this->getLocation()));
        return $this;
    }

    /**
     * @param string $content
     * @return FileInterface
     */
    public function setContent(string $content): FileInterface {
        $this->data = parse_ini_string($content, true, $this->scannerMode) ?? [];
        return $this;
    }

    /**
     * @param bool $sections
     * @return IniFile
     */
    public function setSections(bool $sections): self {
        $this->sections = $sections;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->toIniString($this->data, $this->sections);
    }

    /**
     * @param array|string|int|float|bool|null $data      could be array,scalar or null
     * @param bool                             $sections  determines if sections are used or not
     * @param string|null                      $keyPrefix key parameter for recursive key passthrough - do not use on initial call
     * @return string
     */
    protected function toIniString($data, bool $sections, ?string $keyPrefix = null): string {

        if (!is_array($data)) {
            return '';
        }

        ksort($data);
        $keys = array_keys($data);
        $isNumericArray = ($keys === range(0, count($keys) - 1));

        $result = '';
        foreach ($data as $key => $value) {

            if (is_array($value)) {

                ksort($value);

                if ($sections) {
                    $result .= PHP_EOL;
                    $result .= '[' . $key . ']' . PHP_EOL;
                    $key = null;
                } elseif ($keyPrefix !== null && $isNumericArray) {
                    $key = $keyPrefix . '[]';
                } elseif ($keyPrefix !== null) {
                    $key = $keyPrefix . '[' . $key . ']';
                }

                $result .= $this->toIniString($value, false, $key);
            } else {

                if ($keyPrefix !== null) {
                    if ($isNumericArray) {
                        $key = $keyPrefix . '[]';
                    } else {
                        $key = $keyPrefix . '[' . $key . ']';
                    }
                }

                if (is_bool($value)) {
                    $value = $value ? '"1"' : '';
                } else {
                    $value = '"' . $value . '"';
                }
                $result .= $key . ' = ' . $value . PHP_EOL;
            }
        }

        return rtrim($result, PHP_EOL);
    }
    /**
     * @return bool
     */
    public function hasSections(): bool {
        return $this->sections;
    }
    /**
     * @return array
     */
    public function getData(): array {
        return $this->data;
    }

    /**
     * @param array $data
     * @return IniFile
     */
    public function setData(array $data): IniFile {
        $this->data = $data;
        return $this;
    }
}

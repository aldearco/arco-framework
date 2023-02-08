<?php

namespace Arco\Translation;

use Arco\Translation\Exceptions\TranslationFileDoesNotExistsException;
use Arco\Translation\Exceptions\TranslationFileTypeNotAllowedException;
use Arco\Translation\Interfaces\Loader;

class FileLoader implements Loader {
    /**
     * Path to translation files
     *
     * @var string
     */
    protected string $path;

    /**
     * Allowed file types for translation files
     *
     * @var array
     */
    protected array $allowedFileTypes = [
        'php', 'json'
    ];

    /**
     * File type of translation files
     *
     * @var string
     */
    protected string $fileType;

    /**
     * FileLoader constructor.
     *
     * @param string $path
     * @param string $fileType
     */
    public function __construct(string $path, string $fileType) {
        $this->path = $path;
        $this->fileType = $fileType;
    }

    /**
     * Check if file at the specified path exists
     *
     * @param string $path
     * @return bool
     * @throws TranslationFileDoesNotExistsException
     */
    protected function fileExists(string $path): bool {
        if (file_exists($path)) {
            return true;
        }

        throw new TranslationFileDoesNotExistsException("File does not exists: $path");
    }

    /**
     * Check if the file type passed to the constructor is allowed
     *
     * @return bool
     * @throws TranslationFileTypeNotAllowedException
     */
    protected function isFileTypeAllowed(): bool {
        if (in_array($this->fileType, $this->allowedFileTypes)) {
            return true;
        }

        $allowedFileTypes = implode(', ', $this->allowedFileTypes);
        throw new TranslationFileTypeNotAllowedException("File type '{$this->fileType}' is not allowed. Allowed file types are: {$allowedFileTypes}");
    }

    /**
     * Get the contents of the file at the specified path
     *
     * @param string $path
     * @return mixed
     */
    protected function getFileContents(string $path) {
        return match ($this->fileType) {
            "php" => include $path,
            "json" => json_decode(file_get_contents($path), true)
        };
    }

    /**
     * Load the translation files at the specified path
     *
     * @param string $path
     * @return array
     */
    public function loadPath(string $path) {
        if ($this->fileExists($path) && $this->isFileTypeAllowed()) {
            return $this->getFileContents($path);
        }

        return [];
    }

    /**
     * Load the translation files for the specified locale, group, and namespace
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     * @return mixed
     */
    public function load($locale, $group, $namespace) {
        if ($namespace === '*') {
            return $this->loadPath("{$this->path}/{$locale}/{$group}.{$this->fileType}");
        }

        return $this->loadPath("{$this->path}/{$locale}/{$namespace}/{$group}.{$this->fileType}");
    }
}

<?php

namespace Arco\Translation;

use Arco\Translation\Exceptions\TranslationFileDoesNotExistsException;
use Arco\Translation\Exceptions\TranslationFileTypeNotAllowedException;
use Arco\Translation\Interfaces\Loader;

class FileLoader implements Loader {
    protected string $path;

    protected array $allowedFileTypes = [
        'php', 'json'
    ];

    protected string $fileType;

    public function __construct(string $path, string $fileType) {
        $this->path = $path;
        $this->fileType = $fileType;
    }

    protected function fileExists(string $path): bool {
        if (file_exists($path)) {
            return true;
        }

        throw new TranslationFileDoesNotExistsException("File does not exists: $path");
    }

    protected function isFileTypeAllowed(): bool {
        if (in_array($this->fileType, $this->allowedFileTypes)) {
            return true;
        }

        $allowedFileTypes = implode(', ', $this->allowedFileTypes);
        throw new TranslationFileTypeNotAllowedException("File type '{$this->fileType}' is not allowed. Allowed file types are: {$allowedFileTypes}");
    }

    protected function getFileContents(string $path) {
        return match ($this->fileType) {
            "php" => require_once $path,
            "json" => json_decode(file_get_contents($path), true)
        };
    }

    public function loadPath(string $path) {
        if ($this->fileExists($path) && $this->isFileTypeAllowed()) {
            return $this->getFileContents($path);
        }

        return [];
    }

    public function load($locale, $group, $namespace) {
        if ($namespace === '*') {
            return $this->loadPath("{$this->path}/{$locale}/{$group}.{$this->fileType}");
        }

        return $this->loadPath("{$this->path}/{$locale}/{$namespace}/{$group}.{$this->fileType}");
    }
}

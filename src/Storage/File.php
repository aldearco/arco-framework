<?php

namespace Arco\Storage;

/**
 * File helper.
 */
class File {
    /**
     * Instantiate new file.
     *
     * @param string $path
     * @param mixed $content
     * @param string $type
     */
    public function __construct(
        private mixed $content,
        private string $type,
        private string $originalName,
        private string $size,
    ) {
        $this->content = $content;
        $this->type = $type;
        $this->originalName = $originalName;
        $this->size = $size;
    }

    /**
     * Check if the current file is an image.
     *
     * @return boolean
     */
    public function isImage(): bool {
        return str_starts_with($this->type, "image");
    }

    /**
     * Get file size (bytes).
     *
     * @return int
     */
    public function size(): int {
        return intval($this->size);
    }

    /**
     * Type of the image.
     *
     * @return string|null
     */
    public function extension(): ?string {
        return match ($this->type) {
            "image/jpeg" => "jpeg",
            "image/png" => "png",
            "image/gif" => "gif",
            "image/tiff" => "tiff",
            "image/webp" => "webp",
            "image/avif" => "avif",
            "image/svg+xml" => "svg",
            "image/svg-xml" => "svg",
            "image/heic" => "heic",
            "image/heif" => "heif",
            "video/webm" => "webm",
            "video/mp4" => "mp4",
            "application/pdf" => "pdf",
            "application/json" => "json",
            "application/zip" => "zip",
            "application/vnd.rar" => "rar",
            "text/plain" => "txt",
            "text/csv" => "csv",
            "text/css" => "css",
            "application/rtf" => "rtf",
            "application/msword" => "doc",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document" => "docx",
            "application/vnd.ms-excel" => "xls",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" => "xlsx",
            "application/vnd.ms-powerpoint" => "ppt",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation" => "pptx",
            "application/vnd.oasis.opendocument.text" => "odt",
            "application/vnd.oasis.opendocument.spreadsheet" => "ods",
            "application/vnd.oasis.opendocument.presentation" => "odp",
            default => null,
        };
    }

    /**
     * Store the file.
     *
     * @return string URL.
     */
    public function store(?string $directory = null): string {
        $file = uniqid() . "." . $this->extension();
        $path = is_null($directory) ? $file : "$directory/$file";
        return Storage::put($path, $this->content);
    }
}
